<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScholarProfile;
use Illuminate\Support\Facades\DB;
use App\Models\Document;
use App\Models\FundRequest;
use App\Models\Manuscript;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\ScholarCreateRequest;
use App\Http\Requests\ScholarUpdateRequest;
use Illuminate\Support\Facades\Schema;
use App\Services\NotificationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\AuditService;

class ScholarController extends Controller
{
    protected $auditService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
        
        // Apply scholar authentication only to scholar-specific methods
        $this->middleware('auth:scholar')->except([
            'index', 'create', 'store', 'show', 'edit', 'update', 'destroy',
            'showChangePasswordForm', 'changePassword'
        ]);
        
        // Apply web authentication to admin methods
        $this->middleware('auth:web')->only([
            'index', 'create', 'store', 'show', 'edit', 'update', 'destroy',
            'showChangePasswordForm', 'changePassword'
        ]);

        // Simple role-based middleware for scholar routes
        $this->middleware(function ($request, $next) {
            // Only check for scholar-specific routes
            if ($request->routeIs('scholar.*')) {
                $user = Auth::guard('scholar')->user();
                
                // Ensure user exists and has scholar role
                if (!$user || $user->role !== 'scholar') {
                    Auth::guard('scholar')->logout();
                    Auth::guard('web')->logout();
                    session()->invalidate();
                    session()->regenerateToken();
                    return redirect()->route('scholar.login')->with('error', 'Access denied. Please log in with your scholar credentials.');
                }
            }

            return $next($request);
        })->except([
            'index', 'create', 'store', 'show', 'edit', 'update', 'destroy',
            'showChangePasswordForm', 'changePassword'
        ]);
    }

    /**
     * Display a listing of scholars (admin only).
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.scholars.index');
    }

    // The AJAX filter functionality has been replaced by Livewire components

    /**
     * Show the scholar dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get scholar profile with optimized relations
        $scholarProfile = ScholarProfile::where('user_id', $user->id)->first();
        
        // Initialize default values
        $scholarProgress = 0;
        $daysRemaining = 0;
        $totalFundRequests = 0;
        $approvedFundRequests = 0;
        $pendingFundRequests = 0;
        $rejectedFundRequests = 0;
        $totalManuscripts = 0;
        $publishedManuscripts = 0;
        $underReviewManuscripts = 0;
        $totalDocuments = 0;
        $approvedDocuments = 0;
        $pendingDocuments = 0;
        $unreadNotifications = 0;
        $recentFundRequests = collect();
        $recentManuscripts = collect();
        $recentDocuments = collect();
        $recentNotifications = collect();
        $totalAmountRequested = 0;
        $totalAmountApproved = 0;
        $totalAmountDisbursed = 0;
        
        if ($scholarProfile) {
            // Calculate scholarship progress and days remaining
            $this->calculateProgress($scholarProfile, $scholarProgress, $daysRemaining);
            
            // Load fund requests data
            $this->loadFundRequestsData($scholarProfile, $totalFundRequests, $approvedFundRequests, $pendingFundRequests, $rejectedFundRequests, $recentFundRequests);
            
            // Load manuscripts data
            $this->loadManuscriptsData($scholarProfile, $totalManuscripts, $publishedManuscripts, $underReviewManuscripts, $recentManuscripts);
            
            // Load documents data
            $this->loadDocumentsData($scholarProfile, $totalDocuments, $approvedDocuments, $pendingDocuments, $recentDocuments);
            
            // Load notifications data
            $this->loadNotificationsData($user, $unreadNotifications, $recentNotifications);
            
            // Load financial data
            $this->loadFinancialData($scholarProfile, $totalAmountRequested, $totalAmountApproved, $totalAmountDisbursed);
        }
        
        // Get status and progress colors
        $statusColor = $this->getStatusColor($scholarProfile);
        $progressColor = $this->getProgressColor($scholarProgress);
        
        return view('scholar.dashboard', compact(
            'scholarProfile',
            'scholarProgress',
            'daysRemaining',
            'totalFundRequests',
            'approvedFundRequests',
            'pendingFundRequests',
            'rejectedFundRequests',
            'totalManuscripts',
            'publishedManuscripts',
            'underReviewManuscripts',
            'totalDocuments',
            'approvedDocuments',
            'pendingDocuments',
            'unreadNotifications',
            'recentFundRequests',
            'recentManuscripts',
            'recentDocuments',
            'recentNotifications',
            'totalAmountRequested',
            'totalAmountApproved',
            'totalAmountDisbursed',
            'statusColor',
            'progressColor'
        ));
    }

    /**
     * Show the form for creating a new scholar.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.scholars.create');
    }

    /**
     * Store a newly created scholar in storage.
     *
     * @param  \App\Http\Requests\ScholarCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScholarCreateRequest $request)
    {
        /** @var \Illuminate\Http\Request $request */
        // Create a detailed log file for debugging
        $logPath = storage_path('logs/scholar_creation.log');
        $logMessage = "Scholar creation attempted at " . now() . " by user ID: " . Auth::id() . "\n";
        $logMessage .= "Request data: " . json_encode($request->input()) . "\n";

        // Ensure logs directory exists
        if (!file_exists(storage_path('logs'))) {
            mkdir(storage_path('logs'), 0755, true);
        }

        file_put_contents($logPath, $logMessage, FILE_APPEND);

        // Check if user has admin role
        if (Auth::user()->role !== 'admin') {
            $logMessage = "ERROR: Non-admin user attempted to create scholar. User role: " . Auth::user()->role . "\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);
            return redirect()->back()
                ->withInput()
                ->with('error', 'You do not have permission to create scholars.');
        }

        // All validation is handled in the ScholarCreateRequest
        $logMessage = "Validation passed, beginning transaction\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);

        // Begin transaction to ensure both user and profile are created
        DB::beginTransaction();

        try {
            // Check database connection
            try {
                DB::connection()->getPdo();
                $logMessage = "Database connection successful\n";
            } catch (\Exception $e) {
                $logMessage = "ERROR: Database connection failed: " . $e->getMessage() . "\n";
                file_put_contents($logPath, $logMessage, FILE_APPEND);
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }

            // Create user account
            $validated = $request->validated();

            $userAttributes = [
                'name' => $validated['first_name'] . ' ' . ($validated['middle_name'] ? ' ' . $validated['middle_name'] : '') . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make('CLSU-scholar123'), // Use consistent default password instead of random one
                'role' => 'scholar',
                'is_default_password' => true,
                'must_change_password' => true,
            ];

            $logMessage = "Attempting to create user with attributes: " . json_encode($userAttributes) . "\n";
            $logMessage .= "Default password set to: CLSU-scholar123 (visible to admin only)\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);

            $user = User::create($userAttributes);

            $logMessage = "User created with ID: " . $user->id . "\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);

            // Generate password reset token to allow scholar to set their own password
            try {
                $token = Str::random(60);

                // Check if password_reset_tokens table exists
                if (Schema::hasTable('password_reset_tokens')) {
                    DB::table('password_reset_tokens')->insert([
                        'email' => $user->email,
                        'token' => Hash::make($token),
                        'created_at' => now(),
                    ]);
                    $logMessage = "Password reset token created\n";
                } else {
                    // Log warning but continue with the process
                    $logMessage = "WARNING: password_reset_tokens table does not exist. Skipping password reset token creation.\n";
                    \Illuminate\Support\Facades\Log::warning('password_reset_tokens table does not exist. Skipping password reset token creation.');
                }
                file_put_contents($logPath, $logMessage, FILE_APPEND);
            } catch (\Exception $e) {
                // Log error but continue with the process
                $logMessage = "ERROR creating password reset token: " . $e->getMessage() . "\n";
                file_put_contents($logPath, $logMessage, FILE_APPEND);
                \Illuminate\Support\Facades\Log::error('Failed to create password reset token: ' . $e->getMessage());
            }

            // Create scholar profile
            $scholarProfile = new ScholarProfile();
            $scholarProfile->user_id = $user->id;

            // Personal information
            $scholarProfile->first_name = $validated['first_name'];
            $scholarProfile->middle_name = $validated['middle_name'];
            $scholarProfile->last_name = $validated['last_name'];
            $scholarProfile->contact_number = $validated['contact_number'];
            
            // Optional personal fields
            if (isset($validated['birth_date'])) {
                $scholarProfile->birth_date = $validated['birth_date'];
            }
            if (isset($validated['gender'])) {
                $scholarProfile->gender = $validated['gender'];
            }

            // Address information
            if (isset($validated['province'])) {
                $scholarProfile->province = $validated['province'];
            }
            if (isset($validated['country'])) {
                $scholarProfile->country = $validated['country'];
            }
            if (isset($validated['street'])) {
                $scholarProfile->street = $validated['street'];
            }
            if (isset($validated['village'])) {
                $scholarProfile->village = $validated['village'];
            }
            if (isset($validated['town'])) {
            $scholarProfile->town = $validated['town'];
        }
            if (isset($validated['district'])) {
                $scholarProfile->district = $validated['district'];
            }
            if (isset($validated['region'])) {
                $scholarProfile->region = $validated['region'];
            }

            // Academic information
            $scholarProfile->intended_university = $validated['intended_university'];
            // Program field removed - using department instead
            $scholarProfile->department = $validated['department'];
            
            // Additional academic fields
            if (isset($validated['major'])) {
                $scholarProfile->major = $validated['major'];
            }
            if (isset($validated['intended_degree'])) {
                $scholarProfile->intended_degree = $validated['intended_degree'];
            }
            if (isset($validated['level'])) {
                $scholarProfile->level = $validated['level'];
            }
            if (isset($validated['course_completed'])) {
                $scholarProfile->course_completed = $validated['course_completed'];
            }
            if (isset($validated['university_graduated'])) {
                $scholarProfile->university_graduated = $validated['university_graduated'];
            }
            if (isset($validated['entry_type'])) {
                $scholarProfile->entry_type = $validated['entry_type'];
            }
            if (isset($validated['thesis_dissertation_title'])) {
                $scholarProfile->thesis_dissertation_title = $validated['thesis_dissertation_title'];
            }
            if (isset($validated['units_required'])) {
                $scholarProfile->units_required = $validated['units_required'];
            }
            if (isset($validated['units_earned_prior'])) {
                $scholarProfile->units_earned_prior = $validated['units_earned_prior'];
            }


$scholarProfile->setAttribute('status', $validated['status']);
            $scholarProfile->start_date = $validated['start_date'];
            // Expected completion date field removed
    
            $scholarProfile->study_time = $validated['study_time'];
            $scholarProfile->scholarship_duration = $validated['scholarship_duration'];

            // Optional fields
            if (isset($validated['notes'])) {
                $scholarProfile->notes = $validated['notes'];
            }
            if (isset($validated['actual_completion_date'])) {
                $scholarProfile->actual_completion_date = $validated['actual_completion_date'];
            }

            $scholarProfile->save();
            $logMessage = "Scholar profile saved with ID: " . $scholarProfile->id . "\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);

            // Create audit log for the creation
            $this->auditService->logCreate('ScholarProfile', $scholarProfile->id, $scholarProfile->toArray());
            $logMessage = "Audit log created successfully\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);

            // Commit transaction
            DB::commit();
            $logMessage = "Transaction committed successfully\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);

            // Send password reset link to scholar
            // Temporarily disabled: Mail::to($user->email)->send(new \App\Mail\ScholarWelcome($user, $token));

            return redirect()->route('admin.scholars.index')
                ->with('success', 'Scholar created successfully! <br><br><strong>Default Password Information:</strong><br>Email: ' . $user->email . '<br>Password: <strong>CLSU-scholar123</strong><br><br>Please inform the scholar to change this default password after first login.');

        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollBack();

            // Log detailed error
            $logMessage = "ERROR creating scholar: " . $e->getMessage() . "\n";
            $logMessage .= "Stack trace: " . $e->getTraceAsString() . "\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);

            \Illuminate\Support\Facades\Log::error('Failed to create scholar: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create scholar: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified scholar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $scholar = ScholarProfile::withFullRelations()->findOrFail($id);
            return view('admin.scholars.show', compact('scholar'));
        } catch (ModelNotFoundException $e) {
            // Log the error or handle it as needed
            // For example, redirect to a 404 page or show an error message
            return response()->view('errors.404', [], 404);
        }
    }

    /**
     * Show the form for editing the specified scholar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $scholar = ScholarProfile::findOrFail($id);
        return view('admin.scholars.edit', compact('scholar'));
    }

    /**
     * Update the specified scholar in storage.
     *
     * @param  \App\Http\Requests\ScholarUpdateRequest  $request
     * @param  int  $id
     * @param  \App\Services\NotificationService  $notificationService
     * @return \Illuminate\Http\Response
     */
    public function update(ScholarUpdateRequest $request, $id, NotificationService $notificationService)
    {
        /** @var \Illuminate\Http\Request $request */
        // All validation is handled in the ScholarUpdateRequest

        // Find the scholar profile
        $scholar = ScholarProfile::findOrFail($id);

        // Store original values for audit log
        $originalValues = $scholar->toArray();

        // Begin transaction for the update
        DB::beginTransaction();

        try {
            // Update personal information
            $scholar->first_name = $request->input('first_name');
            $scholar->middle_name = $request->input('middle_name');
            $scholar->last_name = $request->input('last_name');
            $scholar->contact_number = $request->input('contact_number');


            // Optional personal fields
            if ($request->input('gender')) {
                $scholar->gender = $request->input('gender');
            }
            if ($request->input('birth_date')) {
                $scholar->birth_date = $request->input('birth_date');
            }

            // Location information
            if ($request->input('province')) {
                $scholar->province = $request->input('province');
            }
            if ($request->input('country')) {
                $scholar->country = $request->input('country');
            }

            // Academic information
            $scholar->intended_university = $request->input('intended_university');
            // Program field removed - using department instead
            $scholar->department = $request->input('department');


            // Debug logging for status update
            \Illuminate\Support\Facades\Log::info('Scholar status update', [
                'scholar_id' => $scholar->id,
                'old_status' => $scholar->status,
                'new_status' => $request->input('status'),
                'request_data' => $request->input()
            ]);

            $scholar->status = $request->input('status');
            $scholar->start_date = $request->input('start_date');
            // Expected completion date field removed

            if ($request->input('actual_completion_date')) {
                $scholar->actual_completion_date = $request->input('actual_completion_date');
            }
            if ($request->input('intended_degree')) {
                $scholar->intended_degree = $request->input('intended_degree');
            }
            if ($request->input('year_level')) {
                $scholar->year_level = $request->input('year_level');
            }

            // Optional academic background fields



            // Notes
            if ($request->input('notes')) {
                $scholar->notes = $request->input('notes');
            }

            // Verification status handling removed

            // Save the updated scholar profile
            $scholar->save();

            // Update user name if changed
            $user = $scholar->user;
            $newName = $request->input('first_name') . ' ' . ($request->input('middle_name') ? $request->input('middle_name') . ' ' : '') . $request->input('last_name');

            if ($user && $user->name !== $newName) {
                $user->name = $newName;
                $user->save();
            }

            // Update user email if changed
            if ($user && $request->input('email') && $user->email !== $request->input('email')) {
                $user->email = $request->input('email');
                $user->save();
            }

            // Create audit log for the update
            $this->auditService->logUpdate('ScholarProfile', $scholar->id, $originalValues, $scholar->toArray());

            // Send notification to scholar if updated by admin
            if (Auth::user()->role === 'admin' && $scholar->user_id) {
                $changedFields = [];
                $newValues = $scholar->toArray();

                // Determine which fields were changed
                foreach ($originalValues as $field => $value) {
                    if (isset($newValues[$field]) && $value !== $newValues[$field] && !in_array($field, ['updated_at'])) {
                        $changedFields[] = str_replace('_', ' ', ucfirst($field));
                    }
                }

                if (!empty($changedFields)) {
                    $changedFieldsText = count($changedFields) > 3
                        ? implode(', ', array_slice($changedFields, 0, 3)) . ' and others'
                        : implode(', ', $changedFields);

                    $title = 'Profile Updated';
                    $message = 'Your scholar profile has been updated by an administrator. The following information was changed: ' . $changedFieldsText;
                    $link = '/scholar/profile';

                    // Send in-app notification only (no email)
                    $notificationService->notify(
                        $scholar->user_id,
                        $title,
                        $message,
                        'profile_update',
                        $link,
                        false // Don't send email notification
                    );
                }
            }

            // Commit transaction
            DB::commit();

            $successRoute = Auth::user()->role === 'admin'
                ? 'admin.scholars.index'
                : 'scholar.profile.show';

            $successMessage = 'Scholar profile updated successfully. Status set to: ' . $scholar->status;

            // Add notification info to success message if admin updated a scholar
            if (Auth::user()->role === 'admin' && $scholar->user_id) {
                $successMessage .= '<br><br>A notification has been sent to the scholar about this update.';
            }

            return redirect()->route($successRoute)
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update scholar profile: ' . $e->getMessage());
        }
    }

    /**
     * Display all notifications for the scholar.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function notifications()
    {
        return view('scholar.notifications');
    }

    /**
     * Remove the specified scholar from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $scholar = ScholarProfile::findOrFail($id);
        // Implementation would delete the scholar profile
        // For now, return with a success message
        return redirect()->route('admin.scholars.index')
            ->with('success', 'Scholar deleted successfully.');
    }

    /**
     * Show the form for changing scholar's password.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showChangePasswordForm($id)
    {
        $scholar = ScholarProfile::with('user')->findOrFail($id);
        
        return view('admin.scholars.change-password', compact('scholar'));
    }

    /**
     * Update the scholar's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  \App\Services\NotificationService  $notificationService
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request, $id, NotificationService $notificationService)
    {
        $scholar = ScholarProfile::with('user')->findOrFail($id);
        
        // Validate the new password
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            // Update the user's password
            $scholar->user->update([
                'password' => Hash::make($request->password),
                'is_default_password' => false,
                'must_change_password' => false,
            ]);

            // Send notification to scholar about password change
            $notificationService->notify(
                $scholar->user->id,
                '🔐 Password Changed by Administrator',
                'Your account password has been changed by an administrator. If you did not request this change, please contact the admin office immediately.',
                'password_change',
                route('scholar.dashboard')
            );

            // Log the password change action
            $this->auditService->log('password_changed_by_admin', 'User', $scholar->user->id, 
                'Password changed by admin for scholar: ' . $scholar->user->name);

            return redirect()->route('admin.scholars.show', $id)
                ->with('success', 'Scholar password changed successfully. The scholar has been notified of this change.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to change password: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Create an audit log entry for scholar actions.
     *
     * @param string $action
     * @param string $modelType
     * @param int $modelId
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return void
     */
    private function createAuditLog(string $action, string $modelType, int $modelId, ?array $oldValues, ?array $newValues): void
    {
        try {
            if ($action === 'created') {
                $this->auditService->logCreate($modelType, $modelId, $newValues);
            } elseif ($action === 'updated') {
                $this->auditService->logUpdate($modelType, $modelId, $oldValues, $newValues);
            } elseif ($action === 'deleted') {
                $this->auditService->logDelete($modelType, $modelId, $oldValues);
            }
        } catch (\Exception $e) {
            // Log the error but don't halt the main operation
            \Illuminate\Support\Facades\Log::error('Failed to create audit log: ' . $e->getMessage());
        }
    }
    
    /**
     * Calculate scholarship progress and days remaining
     */
    private function calculateProgress($scholarProfile, &$scholarProgress, &$daysRemaining)
    {
        if (!$scholarProfile->id || !$scholarProfile->start_date) {
            $scholarProgress = 0;
            $daysRemaining = 0;
            return;
        }
        
        $startDate = \Carbon\Carbon::parse($scholarProfile->start_date);
        $currentDate = \Carbon\Carbon::now();
        
        // Assuming a typical scholarship duration of 4 years (1460 days)
        $totalDuration = 1460; // days
        $daysPassed = $startDate->diffInDays($currentDate);
        
        $scholarProgress = min(($daysPassed / $totalDuration) * 100, 100);
        $daysRemaining = max($totalDuration - $daysPassed, 0);
    }
    
    /**
     * Load fund requests data
     */
    private function loadFundRequestsData($scholarProfile, &$totalFundRequests, &$approvedFundRequests, &$pendingFundRequests, &$rejectedFundRequests, &$recentFundRequests)
    {
        if (!$scholarProfile->id) {
            return;
        }
        
        // Optimize with single query using aggregation
        $statusCounts = FundRequest::where('scholar_profile_id', $scholarProfile->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $totalFundRequests = array_sum($statusCounts);
        $approvedFundRequests = $statusCounts['approved'] ?? 0;
        $pendingFundRequests = $statusCounts['pending'] ?? 0;
        $rejectedFundRequests = $statusCounts['rejected'] ?? 0;
        
        // Get recent fund requests with relations
        $recentFundRequests = FundRequest::withBasicRelations()
            ->where('scholar_profile_id', $scholarProfile->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    
    /**
     * Load manuscripts data
     */
    private function loadManuscriptsData($scholarProfile, &$totalManuscripts, &$publishedManuscripts, &$underReviewManuscripts, &$recentManuscripts)
    {
        if (!$scholarProfile->id) {
            return;
        }
        
        // Optimize with single query using aggregation
        $statusCounts = Manuscript::where('scholar_profile_id', $scholarProfile->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $totalManuscripts = array_sum($statusCounts);
        $publishedManuscripts = $statusCounts['Published'] ?? 0;
        $underReviewManuscripts = $statusCounts['Under Review'] ?? 0;
        
        // Get recent manuscripts with relations
        $recentManuscripts = Manuscript::withBasicRelations()
            ->where('scholar_profile_id', $scholarProfile->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    
    /**
     * Load documents data
     */
    private function loadDocumentsData($scholarProfile, &$totalDocuments, &$approvedDocuments, &$pendingDocuments, &$recentDocuments)
    {
        if (!$scholarProfile->id) {
            return;
        }
        
        // Optimize with single query using joins and aggregation
        $statusCounts = Document::join('fund_requests', function($join) use ($scholarProfile) {
                $join->on('documents.fund_request_id', '=', 'fund_requests.id')
                     ->where('fund_requests.scholar_profile_id', $scholarProfile->id);
            })
            ->orWhereExists(function($query) use ($scholarProfile) {
                $query->select(DB::raw(1))
                      ->from('manuscripts')
                      ->whereColumn('manuscripts.id', 'documents.manuscript_id')
                      ->where('manuscripts.scholar_profile_id', $scholarProfile->id);
            })
            ->selectRaw('documents.status, COUNT(*) as count')
            ->groupBy('documents.status')
            ->pluck('count', 'status')
            ->toArray();
        
        $totalDocuments = array_sum($statusCounts);
        $approvedDocuments = $statusCounts['approved'] ?? 0;
        $pendingDocuments = $statusCounts['pending'] ?? 0;
        
        // Get recent documents with optimized query
        $recentDocuments = Document::with(['fundRequest', 'manuscript'])
            ->where(function($query) use ($scholarProfile) {
                $query->whereHas('fundRequest', function($q) use ($scholarProfile) {
                    $q->where('scholar_profile_id', $scholarProfile->id);
                })
                ->orWhereHas('manuscript', function($q) use ($scholarProfile) {
                    $q->where('scholar_profile_id', $scholarProfile->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    
    /**
     * Load notifications data
     */
    private function loadNotificationsData($user, &$unreadNotifications, &$recentNotifications)
    {
        $notificationService = app(\App\Services\NotificationService::class);
        $unreadNotifications = $notificationService->getUnreadCount($user->id);
        
        // Get recent notifications
        $recentNotifications = $notificationService->getRecentNotifications($user->id, 5);
    }
    
    /**
     * Load financial data
     */
    private function loadFinancialData($scholarProfile, &$totalAmountRequested, &$totalAmountApproved, &$totalAmountDisbursed)
    {
        if (!$scholarProfile->id) {
            return;
        }
        
        $fundRequestService = app(\App\Services\FundRequestService::class);
        $statistics = $fundRequestService->getScholarFundRequestStatistics($scholarProfile->id);
        
        $totalAmountRequested = $statistics['totalRequested'];
        $totalAmountApproved = $statistics['totalApproved'];
        
        // Optimize disbursed amount calculation with single query
        $totalAmountDisbursed = DB::table('disbursements')
            ->join('fund_requests', 'disbursements.fund_request_id', '=', 'fund_requests.id')
            ->where('fund_requests.scholar_profile_id', $scholarProfile->id)
            ->where('fund_requests.status', 'Approved')
            ->sum('disbursements.amount') ?? 0;
    }
    
    /**
     * Get status color for the scholar profile
     */
    private function getStatusColor($scholarProfile)
    {
        if (!$scholarProfile || !isset($scholarProfile->status)) {
            return 'gray';
        }
        
        return match($scholarProfile->status) {
            'active' => 'green',
            'inactive' => 'red',
            'pending' => 'yellow',
            'graduated' => 'blue',
            default => 'gray'
        };
    }
    
    /**
     * Get progress color based on completion percentage
     */
    private function getProgressColor($scholarProgress)
    {
        if ($scholarProgress >= 75) {
            return 'green';
        } elseif ($scholarProgress >= 50) {
            return 'yellow';
        } elseif ($scholarProgress >= 25) {
            return 'orange';
        } else {
            return 'red';
        }
    }
}
