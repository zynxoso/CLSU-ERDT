<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarProfile;
use App\Models\CustomNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class StipendController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->notificationService = $notificationService;
    }

    /**
     * Display the stipend notification page
     */
    public function index()
    {
        return view('admin.stipends.index');
    }

    /**
     * Show the bulk notification form
     */
    public function showBulkNotify()
    {
        // Get active scholars count for the view
        $activeScholarsCount = ScholarProfile::whereStatus('Active')->count();
        
        // Validate that notifications can be sent
        $validationResult = $this->validateNotificationEligibility();
        
        if (!$validationResult['can_send']) {
            // Redirect back with validation error
            return redirect()->route('admin.stipends.index')
                ->with('validation_error', $validationResult['message'])
                ->with('error_type', $validationResult['error_type']);
        }
        
        return view('admin.stipends.bulk-notify', compact('activeScholarsCount'));
    }

    /**
     * Send stipend available notification to all active scholars
     */
    public function sendBulkNotifications(Request $request)
    {
        try {
            // Validate request structure
            $this->validateBulkNotificationRequest($request);
            
            // Check notification eligibility
            $validationResult = $this->validateNotificationEligibility();
            
            if (!$validationResult['can_send']) {
                return $this->createValidationErrorResponse(
                    $validationResult['error_type'],
                    $validationResult['message'],
                    $validationResult['data'] ?? []
                );
            }

            // Get all active scholars with enhanced validation
            $activeScholars = $this->getEligibleScholars();

            if ($activeScholars->isEmpty()) {
                return $this->createValidationErrorResponse(
                    'NO_ACTIVE_SCHOLARS',
                    'No active scholars found to notify. Please ensure there are scholars with "Active" status.',
                    ['active_scholars_count' => 0]
                );
            }

            // Send notifications with detailed tracking
            $result = $this->processNotifications($activeScholars);

            // Log the notification activity with enhanced details
            $this->logNotificationActivity($result, $activeScholars->count());

            // Return structured response
            return $this->createSuccessResponse($result, $activeScholars->count());

        } catch (ValidationException $e) {
            return $this->createValidationErrorResponse(
                'VALIDATION_ERROR',
                $e->getMessage(),
                ['errors' => $e->errors()]
            );
        } catch (\Exception $e) {
            Log::error('Critical error in stipend notification process', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            return $this->createValidationErrorResponse(
                'SYSTEM_ERROR',
                'A system error occurred while processing notifications. Please try again or contact support if the problem persists.',
                ['error_code' => 'STIPEND_SEND_FAILED']
            );
        }
    }

    /**
     * Get statistics for active scholars
     */
    public function getStats()
    {
        try {
            $activeScholarsCount = ScholarProfile::whereStatus('Active')->count();
            $totalNotifications = CustomNotification::where('type', 'stipend_notification')->count();
            
            // Get last stipend notification info
            $lastNotification = CustomNotification::where('type', 'stipend_notification')
                ->orderBy('created_at', 'desc')
                ->first();
            
            $lastNotificationDate = null;
            $nextAllowedDate = null;
            $canSendNotification = true;
            $daysRemaining = 0;
            
            if ($lastNotification) {
                $lastNotificationDate = $lastNotification->created_at->format('M j, Y');
                $nextAllowedDate = $lastNotification->created_at->addMonth()->format('M j, Y');
                $daysRemaining = ceil(Carbon::now()->diffInDays($lastNotification->created_at->addMonth(), false));
                $canSendNotification = $daysRemaining <= 0;
            }
            
            return response()->json([
                'success' => true,
                'active_scholars' => $activeScholarsCount,
                'total_notifications' => $totalNotifications,
                'last_notification_date' => $lastNotificationDate,
                'next_allowed_date' => $nextAllowedDate,
                'can_send_notification' => $canSendNotification,
                'days_remaining' => max(0, $daysRemaining),
                'validation_status' => $this->getValidationStatus($activeScholarsCount, $canSendNotification)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get stipend statistics', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to load stipend statistics. Please refresh the page.',
                'error_type' => 'STATS_ERROR'
            ], 500);
        }
    }

    /**
     * Validate bulk notification request
     */
    private function validateBulkNotificationRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Add any specific validation rules if needed
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Validate notification eligibility
     */
    private function validateNotificationEligibility(): array
    {
        // Check if a stipend notification was already sent within the last month
        $lastNotification = CustomNotification::where('type', 'stipend_notification')
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastNotification) {
            $nextAllowedDate = $lastNotification->created_at->addMonth();
            $daysRemaining = Carbon::now()->diffInDays($nextAllowedDate, false);
            
            if ($daysRemaining > 0) {
                return [
                    'can_send' => false,
                    'error_type' => 'FREQUENCY_LIMIT',
                    'message' => "Notification frequency limit reached. You can send the next notification on {$nextAllowedDate->format('M j, Y')} ({$daysRemaining} days remaining).",
                    'data' => [
                        'last_sent' => $lastNotification->created_at->format('M j, Y'),
                        'next_allowed' => $nextAllowedDate->format('M j, Y'),
                        'days_remaining' => $daysRemaining
                    ]
                ];
            }
        }

        return ['can_send' => true];
    }

    /**
     * Get eligible scholars for notification
     */
    private function getEligibleScholars()
    {
        return ScholarProfile::with(['user' => function($query) {
                $query->whereNotNull('email')
                      ->where('email', '!=', '');
            }])
            ->whereStatus('Active')
            ->whereHas('user', function($query) {
                $query->whereNotNull('email')
                      ->where('email', '!=', '');
            })
            ->get();
    }

    /**
     * Process notifications to all eligible scholars
     */
    private function processNotifications($activeScholars): array
    {
        $sent = 0;
        $failed = 0;
        $errors = [];
        $successfulRecipients = [];
        $failedRecipients = [];

        foreach ($activeScholars as $scholar) {
            try {
                $success = $this->notificationService->notify(
                    $scholar->user->id,
                    'Your Stipend is Now Available',
                    'Dear Scholar, We are pleased to inform you that your stipend is now available. Please contact the ERDT office for collection details. Best regards, CLSU-ERDT Team',
                    'stipend_notification',
                    route('scholar.dashboard')
                );

                if ($success) {
                    $sent++;
                    $successfulRecipients[] = [
                        'name' => $scholar->user->name,
                        'email' => $scholar->user->email,
                        'scholar_id' => $scholar->id
                    ];
                } else {
                    $failed++;
                    $errorMsg = "Failed to send notification to {$scholar->user->name}";
                    $errors[] = $errorMsg;
                    $failedRecipients[] = [
                        'name' => $scholar->user->name,
                        'email' => $scholar->user->email,
                        'error' => 'Notification service returned false'
                    ];
                }
            } catch (\Exception $e) {
                $failed++;
                $errorMsg = "Error sending to {$scholar->user->name}: {$e->getMessage()}";
                $errors[] = $errorMsg;
                $failedRecipients[] = [
                    'name' => $scholar->user->name,
                    'email' => $scholar->user->email,
                    'error' => $e->getMessage()
                ];
            }
        }

        return [
            'sent' => $sent,
            'failed' => $failed,
            'errors' => $errors,
            'successful_recipients' => $successfulRecipients,
            'failed_recipients' => $failedRecipients
        ];
    }

    /**
     * Log notification activity with enhanced details
     */
    private function logNotificationActivity(array $result, int $totalScholars): void
    {
        Log::info("Stipend notification batch completed", [
            'sent' => $result['sent'],
            'failed' => $result['failed'],
            'total_scholars' => $totalScholars,
            'success_rate' => $totalScholars > 0 ? round(($result['sent'] / $totalScholars) * 100, 2) : 0,
            'sent_by' => Auth::user()->name,
            'sent_by_id' => Auth::id(),
            'timestamp' => Carbon::now()->toISOString(),
            'successful_recipients' => count($result['successful_recipients']),
            'failed_recipients' => count($result['failed_recipients'])
        ]);

        // Log errors separately if any
        if (!empty($result['errors'])) {
            Log::warning("Stipend notification errors occurred", [
                'errors' => $result['errors'],
                'failed_recipients' => $result['failed_recipients']
            ]);
        }
    }

    /**
     * Create success response
     */
    private function createSuccessResponse(array $result, int $totalScholars): \Illuminate\Http\JsonResponse
    {
        $successRate = $totalScholars > 0 ? round(($result['sent'] / $totalScholars) * 100, 2) : 0;
        
        $message = $result['sent'] > 0 
            ? "Successfully sent notifications to {$result['sent']} scholar(s)" . ($result['failed'] > 0 ? ". {$result['failed']} failed to send." : ".")
            : "No notifications were sent successfully.";

        return response()->json([
            'success' => $result['sent'] > 0,
            'message' => $message,
            'data' => [
                'notifications_sent' => $result['sent'],
                'notifications_failed' => $result['failed'],
                'total_scholars' => $totalScholars,
                'success_rate' => $successRate,
                'timestamp' => Carbon::now()->toISOString(),
                'next_allowed_date' => Carbon::now()->addMonth()->format('M j, Y')
            ],
            'errors' => $result['failed'] > 0 ? $result['errors'] : [],
            'validation_status' => 'SUCCESS'
        ]);
    }

    /**
     * Create validation error response
     */
    private function createValidationErrorResponse(string $errorType, string $message, array $data = []): \Illuminate\Http\JsonResponse
    {
        $statusCode = match($errorType) {
            'FREQUENCY_LIMIT' => 422,
            'NO_ACTIVE_SCHOLARS' => 404,
            'VALIDATION_ERROR' => 422,
            'UNAUTHORIZED' => 403,
            'SYSTEM_ERROR' => 500,
            default => 400
        };

        return response()->json([
            'success' => false,
            'message' => $message,
            'error_type' => $errorType,
            'data' => $data,
            'validation_status' => 'ERROR',
            'timestamp' => Carbon::now()->toISOString()
        ], $statusCode);
    }

    /**
     * Get validation status for stats
     */
    private function getValidationStatus(int $activeScholars, bool $canSend): array
    {
        if ($activeScholars === 0) {
            return [
                'status' => 'WARNING',
                'message' => 'No active scholars available for notifications'
            ];
        }

        if (!$canSend) {
            return [
                'status' => 'BLOCKED',
                'message' => 'Notification frequency limit reached'
            ];
        }

        return [
            'status' => 'READY',
            'message' => 'Ready to send notifications'
        ];
    }
}