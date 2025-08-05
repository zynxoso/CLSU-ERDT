<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarProfile;
use App\Models\CustomNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        return view('admin.stipends.bulk-notify');
    }

    /**
     * Send stipend available notification to all active scholars
     */
    public function sendBulkNotifications(Request $request)
    {
        try {
            // Check if a stipend notification was already sent within the last month
            $lastNotification = CustomNotification::where('type', 'stipend_notification')
                ->where('created_at', '>=', Carbon::now()->subMonth())
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastNotification) {
                $nextAllowedDate = $lastNotification->created_at->addMonth();
                $daysRemaining = Carbon::now()->diffInDays($nextAllowedDate, false);
                
                if ($daysRemaining > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => "A stipend notification was already sent on {$lastNotification->created_at->format('M j, Y')}. You can send the next notification on {$nextAllowedDate->format('M j, Y')} ({$daysRemaining} days remaining)."
                    ], 422);
                }
            }

            // Get all active scholars
            $activeScholars = ScholarProfile::with('user')
                ->whereStatus('Active')
                ->whereHas('user')
                ->get();

            if ($activeScholars->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active scholars found to notify.'
                ], 404);
            }

            $sent = 0;
            $failed = 0;
            $errors = [];

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
                    } else {
                        $failed++;
                        $errors[] = "Failed to send notification to {$scholar->user->name}";
                    }
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Error sending to {$scholar->user->name}: {$e->getMessage()}";
                }
            }

            // Log the notification activity
            Log::info("Stipend notification sent to active scholars", [
                'sent' => $sent,
                'failed' => $failed,
                'total_scholars' => $activeScholars->count(),
                'sent_by' => Auth::user()->name
            ]);

            $message = $sent > 0 
                ? "Successfully sent notifications to {$sent} scholar(s)" . ($failed > 0 ? ", {$failed} failed" : "")
                : "Failed to send any notifications";

            return response()->json([
                'success' => $sent > 0,
                'message' => $message,
                'sent' => $sent,
                'failed' => $failed,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send stipend notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for active scholars
     */
    public function getStats()
    {
        try {
            $activeScholarsCount = ScholarProfile::whereStatus('Active')->count();
            
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
                'last_notification_date' => $lastNotificationDate,
                'next_allowed_date' => $nextAllowedDate,
                'can_send_notification' => $canSendNotification,
                'days_remaining' => max(0, $daysRemaining)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics'
            ], 500);
        }
    }
}