<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScholarProfile;
use App\Models\Document;
use App\Models\FundRequest;
use App\Models\Manuscript;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\AuditService;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\CustomNotification;

class AdminController extends Controller
{
    protected $auditService;

    /**
     * Create a new controller instance.
     *
     * @param AuditService $auditService
     * @return void
     */
    public function __construct(AuditService $auditService)
    {
        $this->middleware('auth');
        // Use an anonymous middleware for role checking to avoid the 'admin' string that might be causing issues
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized action. You do not have the required role.');
            }
            return $next($request);
        });
        $this->auditService = $auditService;
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get scholars data
        $scholars = ScholarProfile::all();
        $totalScholars = $scholars->count();
        $pendingScholars = $scholars->where('status', 'Pending')->count();
        $activeScholars = $scholars->where('status', 'Active')->count();
        $recentScholars = $scholars->sortByDesc('created_at')->take(5);

        // Get documents data
        $documents = Document::all();
        $pendingDocuments = $documents->where('status', 'Uploaded')->count();
        $recentDocuments = $documents->sortByDesc('created_at')->take(5);

        // Get fund requests data
        $fundRequests = FundRequest::all();
        $pendingFundRequests = $fundRequests->where('status', 'Pending')->count();
        $approvedRequests = $fundRequests->where('status', 'Approved')->count();
        $recentRequests = $fundRequests->sortByDesc('created_at')->take(3);
        $recentFundRequests = $recentRequests; // Alias for the view
        $pendingRequests = $pendingFundRequests + $pendingDocuments; // Total of all pending requests
        $totalDisbursed = $fundRequests->where('status', 'Approved')->sum('amount');

        // Calculate disbursements for the current month
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $disbursedThisMonth = $fundRequests->where('status', 'Approved')
            ->filter(function ($request) use ($currentMonth, $currentYear) {
                $approvedDate = \Carbon\Carbon::parse($request->updated_at);
                return $approvedDate->month == $currentMonth && $approvedDate->year == $currentYear;
            })->sum('amount');

        // Get manuscripts data
        $manuscripts = Manuscript::all();
        $recentManuscripts = $manuscripts->sortByDesc('created_at')->take(5);

        // Completion metrics
        $completionRate = $scholars->count() > 0 ? round(($scholars->where('status', 'Graduated')->count() / $scholars->count()) * 100) : 0;
        $completionsThisYear = $scholars->where('status', 'Completed')
            ->filter(function($scholar) use ($currentYear) {
                return \Carbon\Carbon::parse($scholar->updated_at)->year == $currentYear;
            })->count();

        // Calculate program distribution
        $programCounts = collect();
        $programGroups = $scholars->groupBy('program');
        $totalScholars = $scholars->count();

        if ($totalScholars > 0) {
            foreach ($programGroups as $program => $scholarsInProgram) {
                $count = $scholarsInProgram->count();
                $percentage = round(($count / $totalScholars) * 100);
                $programCounts->push((object)[
                    'program' => $program ?: 'Not Specified',
                    'count' => $count,
                    'percentage' => $percentage
                ]);
            }

            // Sort by count descending
            $programCounts = $programCounts->sortByDesc('count')->values();
        }

        // Scholar activity and notifications (placeholders)
        $notifications = collect([]);
        $recentScholarActivity = collect([]);

        // Fetch recent audit logs
        $recentLogs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Fetch recent notifications for the admin
        $recentNotifications = CustomNotification::where('user_id', $user->id)
            ->whereIn('type', [
                'App\\Notifications\\NewFundRequestSubmitted',
                'App\\Notifications\\NewManuscriptSubmitted'
            ])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'scholars',
            'totalScholars',
            'pendingScholars',
            'activeScholars',
            'recentScholars',
            'fundRequests',
            'pendingFundRequests',
            'pendingRequests',
            'approvedRequests',
            'recentRequests',
            'recentFundRequests',
            'totalDisbursed',
            'disbursedThisMonth',
            'documents',
            'pendingDocuments',
            'recentDocuments',
            'manuscripts',
            'recentManuscripts',
            'completionRate',
            'completionsThisYear',
            'notifications',
            'recentScholarActivity',
            'recentLogs',
            'programCounts',
            'recentNotifications'
        ));
    }

    /**
     * Show the admin analytics page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function analytics()
    {
        $user = Auth::user();

        // Get scholars data
        $scholars = ScholarProfile::all();
        $scholarsByStatus = [
            'Active' => $scholars->where('status', 'Active')->count(),
            'Pending' => $scholars->where('status', 'Pending')->count(),
            'Completed' => $scholars->where('status', 'Completed')->count(),
            'Inactive' => $scholars->where('status', 'Inactive')->count(),
        ];

        // Get monthly fund data for the last 12 months
        $monthlyData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');
            $startOfMonth = $date->startOfMonth();
            $endOfMonth = $date->endOfMonth();

            $disbursed = FundRequest::where('status', 'Approved')
                ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $requested = FundRequest::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $monthlyData->push([
                'month' => $month,
                'disbursed' => $disbursed,
                'requested' => $requested,
            ]);
        }

        // Get fund request data by type
        $fundRequests = FundRequest::all();
        $fundsByType = $fundRequests->groupBy('request_type')->map->sum('amount');

        // Get document statistics
        $documents = Document::all();
        $documentsByType = $documents->groupBy('document_type')->map->count();

        return view('admin.analytics', compact(
            'user',
            'scholarsByStatus',
            'monthlyData',
            'fundsByType',
            'documentsByType'
        ));
    }

    /**
     * Display the password change form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showChangePasswordForm()
    {
        return view('admin.change-password');
    }

    /**
     * Update the admin's password.
     *
     * @param  \App\Http\Requests\ChangePasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        // Get dynamic password expiry days from settings
        $passwordExpiryDays = \App\Models\SiteSetting::get('password_expiry_days', 90);

        $user->password = Hash::make($validated['new_password']);

        // Clear default password flags
        $user->is_default_password = false;
        $user->must_change_password = false;

        // Set password expiration using dynamic settings
        $user->setPasswordExpiration();

        // Save the user
        $user->save();

        // Clear session warning flag
        session()->forget('password_expiry_warning_shown');
        session()->forget('show_password_modal');

        // Log the password change
        $this->auditService->log('password_changed', 'User', $user->id, 'Password changed successfully');

        // Determine redirect location - default to settings page for better UX
        $redirectRoute = 'admin.settings';

        return redirect()->route($redirectRoute)
            ->with('success', "Password updated successfully. Your password will expire in {$passwordExpiryDays} days.");
    }

    /**
     * Show the admin profile edit form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editProfile()
    {
        return view('admin.profile');
    }

    /**
     * Update the admin profile information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'position' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'position' => $request->position,
        ]);

        $this->auditService->log('profile_updated', 'User', $user->id,
            'Updated profile - Name: ' . $request->name . ', Email: ' . $request->email
        );

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the admin password from profile page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        // Get dynamic password expiry days from settings
        $passwordExpiryDays = \App\Models\SiteSetting::get('password_expiry_days', 90);

        $user->password = Hash::make($request->new_password);

        // Clear default password flags
        $user->is_default_password = false;
        $user->must_change_password = false;

        // Set password expiration using dynamic settings
        $user->setPasswordExpiration();

        // Save the user
        $user->save();

        $this->auditService->log('password_changed', 'User', $user->id, 'Password changed from profile page');

        return redirect()->route('admin.profile.edit')
            ->with('success', "Password updated successfully. Your password will expire in {$passwordExpiryDays} days.");
    }

    /**
     * Update the admin notification preferences.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $user->update([
            'email_notifications' => $request->has('email_notifications'),
            'fund_request_notifications' => $request->has('fund_request_notifications'),
            'document_notifications' => $request->has('document_notifications'),
            'manuscript_notifications' => $request->has('manuscript_notifications'),
        ]);

        $this->auditService->log('notification_preferences_updated', 'User', $user->id, 'Updated notification preferences');

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Notification preferences updated successfully.');
    }

    /**
     * Display the admin settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function settings()
    {
        // Get mock settings data - in a real application, this would come from a settings table
        $settings = (object) [
            'site_name' => 'CLSU-ERDT Scholarship Management System',
            'site_description' => 'Central Luzon State University - Engineering Research and Development for Technology Scholarship Management System',
            'contact_email' => 'erdt@clsu.edu.ph',
            'contact_phone' => '+63 44 456 0680',
            'default_stipend' => 20000,
            'default_book_allowance' => 10000,
            'default_research_allowance' => 50000,
            'max_scholarship_duration' => 36,
            'required_documents' => ['Transcript', 'ID', 'Enrollment', 'Grades'],
        ];

        // Get users for the user management section with pagination
        $users = \App\Models\User::where('role', '!=', 'scholar')->paginate(10);

        return view('admin.settings.index', compact('settings', 'users'));
    }

    /**
     * Update general settings.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        // In a real application, you would save these to a settings table
        // For now, we'll just log the action and show a success message

        $this->auditService->log('settings_updated', 'Settings', null,
            'Updated general settings - Site Name: ' . $request->site_name
        );

        return redirect()->route('admin.settings')
            ->with('success', 'General settings updated successfully.');
    }

    /**
     * Update scholarship settings.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateScholarshipSettings(Request $request)
    {
        $request->validate([
            'default_stipend' => 'required|numeric|min:0',
            'default_book_allowance' => 'required|numeric|min:0',
            'default_research_allowance' => 'required|numeric|min:0',
            'max_scholarship_duration' => 'required|integer|min:1',
            'required_documents' => 'nullable|array',
        ]);

        // In a real application, you would save these to a settings table
        // For now, we'll just log the action and show a success message

        $this->auditService->log('scholarship_settings_updated', 'Settings', null,
            'Updated scholarship settings - Default Stipend: ₱' . number_format($request->default_stipend)
        );

        return redirect()->route('admin.settings')
            ->with('success', 'Scholarship settings updated successfully.');
    }
}
