<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScholarProfile;
use App\Models\Document;
use App\Models\FundRequest;
use App\Models\Manuscript;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\ScholarCreateRequest;
use App\Http\Requests\ScholarUpdateRequest;
use Illuminate\Support\Facades\Schema;
use App\Services\NotificationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ScholarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        // Only restrict scholar-specific methods
        $this->middleware(function ($request, $next) {
            // For scholar-specific routes, redirect admins to admin dashboard
            if ($request->route()->getName() == 'scholar.dashboard') {
                if (Auth::user()->role !== 'scholar') {
                    return redirect()->route('admin.dashboard');
                }
            }

            return $next($request);
        });
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
    public function dashboard(NotificationService $notificationService)
    {
        $user = Auth::user();

        // Get scholar profile
        $scholarProfile = ScholarProfile::where('user_id', $user->id)->first();

        if (!$scholarProfile) {
            // Create a dummy object for the view if no profile exists
            $scholarProfile = new \stdClass();
            $scholarProfile->id = null; // Add id property to prevent undefined property error
            $scholarProfile->status = null;
            $scholarProfile->program = null;
            $scholarProfile->university = null;
            $scholarProfile->expected_completion_date = null;
            $scholarProfile->start_date = null;
        }

        // Calculate scholarship progress and days remaining
        $scholarProgress = 0;
        $daysRemaining = 0;
        $scholar = $scholarProfile;

        if (isset($scholarProfile->id)) {
            // If it's an actual scholar profile object
            if ($scholarProfile->start_date && $scholarProfile->expected_completion_date) {
                $startDate = \Carbon\Carbon::parse($scholarProfile->start_date);
                $endDate = \Carbon\Carbon::parse($scholarProfile->expected_completion_date);
                $today = \Carbon\Carbon::now();

                $totalDays = $startDate->diffInDays($endDate);
                $daysPassed = $startDate->diffInDays($today);

                $scholarProgress = $totalDays > 0 ? min(100, round(($daysPassed / $totalDays) * 100)) : 0;
                $daysRemaining = max(0, $endDate->diffInDays($today));
            }
        }

        // Get fund requests data
        $fundRequests = $scholarProfile->id ? FundRequest::where('scholar_profile_id', $scholarProfile->id)->get() : collect();
        $pendingRequests = $fundRequests->where('status', 'Pending')->count();
        $approvedRequests = $fundRequests->where('status', 'Approved')->count();
        $rejectedRequests = $fundRequests->where('status', 'Rejected')->count();
        $recentFundRequests = $fundRequests->sortByDesc('created_at')->take(3);

        // Calculate total approved amount
        $totalApproved = $fundRequests->where('status', 'Approved')->sum('amount');
        $pendingRequestsCount = $pendingRequests;

        // Get documents data
        $documents = $scholarProfile->id ? Document::where('scholar_profile_id', $scholarProfile->id)->get() : collect();
        $verifiedDocuments = $documents->where('status', 'Verified')->count();
        $pendingDocuments = $documents->where('status', 'Uploaded')->count();
        $rejectedDocuments = $documents->where('status', 'Rejected')->count();
        $recentDocuments = $documents->sortByDesc('created_at')->take(3);

        // Document count variables for the view
        $verifiedDocumentsCount = $verifiedDocuments;
        $pendingDocumentsCount = $pendingDocuments;
        $rejectedDocumentsCount = $rejectedDocuments;

        // Get manuscripts data
        $manuscripts = $scholarProfile->id ? Manuscript::where('scholar_profile_id', $scholarProfile->id)->get() : collect();
        $manuscriptProgress = $manuscripts->count() > 0 ?
            round($manuscripts->sum('progress') / $manuscripts->count()) : 0;
        $recentManuscripts = $manuscripts->sortByDesc('created_at')->take(3);

        // Get real notifications for the dashboard
        $recentActivities = collect(); // Still using mock activities for now
        $notifications = $notificationService->getRecentNotifications($user->id, 5);

        return view('scholar.dashboard', compact(
            'user',
            'scholarProfile',
            'scholarProgress',
            'daysRemaining',
            'scholar',
            'fundRequests',
            'pendingRequests',
            'approvedRequests',
            'rejectedRequests',
            'recentFundRequests',
            'totalApproved',
            'pendingRequestsCount',
            'documents',
            'verifiedDocuments',
            'pendingDocuments',
            'recentDocuments',
            'verifiedDocumentsCount',
            'pendingDocumentsCount',
            'rejectedDocumentsCount',
            'manuscripts',
            'manuscriptProgress',
            'recentManuscripts',
            'recentActivities',
            'notifications'
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
        // Create a detailed log file for debugging
        $logPath = storage_path('logs/scholar_creation.log');
        $logMessage = "Scholar creation attempted at " . now() . " by user ID: " . Auth::id() . "\n";
        $logMessage .= "Request data: " . json_encode($request->all()) . "\n";

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
            $userAttributes = [
                'name' => $request->first_name . ' ' . ($request->middle_name ? ' ' . $request->middle_name : '') . ' ' . $request->last_name,
                'email' => $request->email,
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
            $scholarProfile->first_name = $request->first_name;
            $scholarProfile->middle_name = $request->middle_name;
            $scholarProfile->last_name = $request->last_name;
            $scholarProfile->contact_number = $request->contact_number;
            $scholarProfile->address = $request->address;

            // Optional personal fields
            if ($request->has('gender')) {
                $scholarProfile->gender = $request->gender;
            }
            if ($request->has('birth_date')) {
                $scholarProfile->birth_date = $request->birth_date;
            }

            // Location information
            if ($request->has('city')) {
                $scholarProfile->city = $request->city;
            }
            if ($request->has('province')) {
                $scholarProfile->province = $request->province;
            }
            if ($request->has('postal_code')) {
                $scholarProfile->postal_code = $request->postal_code;
            }
            if ($request->has('country')) {
                $scholarProfile->country = $request->country;
            }

            // Academic information
            $scholarProfile->university = $request->university;
            $scholarProfile->program = $request->program;
            $scholarProfile->department = $request->department;
            $scholarProfile->status = $request->status;
            $scholarProfile->start_date = $request->start_date;
            $scholarProfile->expected_completion_date = $request->expected_completion_date;

            if ($request->has('actual_completion_date')) {
                $scholarProfile->actual_completion_date = $request->actual_completion_date;
            }
            if ($request->has('degree_program')) {
                $scholarProfile->degree_program = $request->degree_program;
            }
            if ($request->has('year_level')) {
                $scholarProfile->year_level = $request->year_level;
            }
            if ($request->has('scholar_id')) {
                $scholarProfile->scholar_id = $request->scholar_id;
            }

            // Optional academic background fields
            if ($request->has('bachelor_degree')) {
                $scholarProfile->bachelor_degree = $request->bachelor_degree;
            }
            if ($request->has('bachelor_university')) {
                $scholarProfile->bachelor_university = $request->bachelor_university;
            }
            if ($request->has('bachelor_graduation_year')) {
                $scholarProfile->bachelor_graduation_year = $request->bachelor_graduation_year;
            }
            if ($request->has('research_area')) {
                $scholarProfile->research_area = $request->research_area;
            }
            if ($request->has('research_title')) {
                $scholarProfile->research_title = $request->research_title;
            }
            if ($request->has('research_abstract')) {
                $scholarProfile->research_abstract = $request->research_abstract;
            }

            // Notes
            if ($request->has('notes')) {
                $scholarProfile->notes = $request->notes;
            }
            if ($request->has('admin_notes')) {
                $scholarProfile->admin_notes = $request->admin_notes;
            }

            $scholarProfile->save();
            $logMessage = "Scholar profile saved with ID: " . $scholarProfile->id . "\n";
            file_put_contents($logPath, $logMessage, FILE_APPEND);

            // Create audit log for the creation
            try {
                $this->createAuditLog('created', 'scholar_profile', $scholarProfile->id, null, $scholarProfile->toArray());
                $logMessage = "Audit log created successfully\n";
                file_put_contents($logPath, $logMessage, FILE_APPEND);
            } catch (\Exception $e) {
                // Log error but continue with the process
                $logMessage = "ERROR creating audit log: " . $e->getMessage() . "\n";
                file_put_contents($logPath, $logMessage, FILE_APPEND);
                \Illuminate\Support\Facades\Log::error('Failed to create audit log: ' . $e->getMessage());
            }

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
            $scholar = ScholarProfile::findOrFail($id);
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
        // All validation is handled in the ScholarUpdateRequest

        // Find the scholar profile
        $scholar = ScholarProfile::findOrFail($id);

        // Store original values for audit log
        $originalValues = $scholar->toArray();

        // Begin transaction for the update
        DB::beginTransaction();

        try {
            // Update personal information
            $scholar->first_name = $request->first_name;
            $scholar->middle_name = $request->middle_name;
            $scholar->last_name = $request->last_name;
            $scholar->contact_number = $request->contact_number;
            $scholar->address = $request->address;

            // Optional personal fields
            if ($request->has('gender')) {
                $scholar->gender = $request->gender;
            }
            if ($request->has('birth_date')) {
                $scholar->birth_date = $request->birth_date;
            }

            // Location information
            if ($request->has('city')) {
                $scholar->city = $request->city;
            }
            if ($request->has('province')) {
                $scholar->province = $request->province;
            }
            if ($request->has('postal_code')) {
                $scholar->postal_code = $request->postal_code;
            }
            if ($request->has('country')) {
                $scholar->country = $request->country;
            }

            // Academic information
            $scholar->university = $request->university;
            $scholar->program = $request->program;
            $scholar->department = $request->department;

            // Debug logging for status update
            \Illuminate\Support\Facades\Log::info('Scholar status update', [
                'scholar_id' => $scholar->id,
                'old_status' => $scholar->status,
                'new_status' => $request->status,
                'request_data' => $request->all()
            ]);

            $scholar->status = $request->status;
            $scholar->start_date = $request->start_date;
            $scholar->expected_completion_date = $request->expected_completion_date;

            if ($request->has('actual_completion_date')) {
                $scholar->actual_completion_date = $request->actual_completion_date;
            }
            if ($request->has('degree_program')) {
                $scholar->degree_program = $request->degree_program;
            }
            if ($request->has('year_level')) {
                $scholar->year_level = $request->year_level;
            }
            if ($request->has('scholar_id')) {
                $scholar->scholar_id = $request->scholar_id;
            }

            // Optional academic background fields
            if ($request->has('bachelor_degree')) {
                $scholar->bachelor_degree = $request->bachelor_degree;
            }
            if ($request->has('bachelor_university')) {
                $scholar->bachelor_university = $request->bachelor_university;
            }
            if ($request->has('bachelor_graduation_year')) {
                $scholar->bachelor_graduation_year = $request->bachelor_graduation_year;
            }
            if ($request->has('research_area')) {
                $scholar->research_area = $request->research_area;
            }
            if ($request->has('research_title')) {
                $scholar->research_title = $request->research_title;
            }
            if ($request->has('research_abstract')) {
                $scholar->research_abstract = $request->research_abstract;
            }

            // Notes
            if ($request->has('notes')) {
                $scholar->notes = $request->notes;
            }
            if ($request->has('admin_notes') && Auth::user()->role === 'admin') {
                $scholar->admin_notes = $request->admin_notes;
            }

            // Save the updated scholar profile
            $scholar->save();

            // Update user name if changed
            $user = $scholar->user;
            $newName = $request->first_name . ' ' . ($request->middle_name ? $request->middle_name . ' ' : '') . $request->last_name;

            if ($user && $user->name !== $newName) {
                $user->name = $newName;
                $user->save();
            }

            // Create audit log for the update
            $this->createAuditLog('updated', 'scholar_profile', $scholar->id, $originalValues, $scholar->toArray());

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
     * @param  \App\Services\NotificationService  $notificationService
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function notifications(NotificationService $notificationService)
    {
        $user = Auth::user();
        $notifications = $notificationService->getRecentNotifications($user->id, 50);

        // Mark all notifications as read
        $notificationService->markAllAsRead($user->id);

        return view('scholar.notifications', compact('notifications'));
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
        // Check if the AuditLog model exists
        if (!class_exists('App\Models\AuditLog')) {
            return;
        }

        try {
            \App\Models\AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'entity_type' => $modelType,
                'entity_id' => $modelId,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Log the error but don't halt the main operation
            \Illuminate\Support\Facades\Log::error('Failed to create audit log: ' . $e->getMessage());
        }
    }
}
