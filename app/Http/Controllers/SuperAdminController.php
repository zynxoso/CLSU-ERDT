<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScholarProfile;
use App\Models\Document;
use App\Models\FundRequest;
use App\Models\Manuscript;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\AuditService;

class SuperAdminController extends Controller
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
        // Use middleware to check for super_admin role
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'super_admin') {
                abort(403, 'Unauthorized action. You do not have super admin privileges.');
            }
            return $next($request);
        });
        $this->auditService = $auditService;
    }

    /**
     * Show the super admin dashboard.
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

        // For year calculation in other parts of the code
        $currentMonth = now()->month;
        $currentYear = now()->year;

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

        // Get all users for user management (super admin specific)
        $allUsers = User::all();
        $adminUsers = $allUsers->where('role', 'admin')->count();
        $scholarUsers = $allUsers->where('role', 'scholar')->count();
        $superAdminUsers = $allUsers->where('role', 'super_admin')->count();
        $recentUsers = $allUsers->sortByDesc('created_at')->take(5);


        return view('super_admin.dashboard', compact(
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
            'documents',
            'pendingDocuments',
            'recentDocuments',
            'manuscripts',
            'recentManuscripts',
            'completionRate',
            'completionsThisYear',
            'programCounts',
            'allUsers',
            'adminUsers',
            'scholarUsers',
            'superAdminUsers',
            'recentUsers'
        ));
    }


    /**
     * Show the user management page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function userManagement(Request $request)
    {
        $query = User::query();

        // Apply role filter if provided
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        $users = $query->get();
        return view('super_admin.user_management', compact('users'));
    }

    /**
     * Show the system settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function systemSettings()
    {
        return view('super_admin.system_settings');
    }

    /**
     * Show the form for editing a user.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('super_admin.edit_user', compact('user'));
    }

    /**
     * Update the specified user.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|string|in:admin,scholar,super_admin',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            ]);

            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->is_active = $request->has('is_active');
        $user->save();

        // Log the user update action
        $this->auditService->log(
            'update',
            'user',
            $user->id,
            'User updated: ' . $user->name,
            $request->all()
        );

        return redirect()->route('super_admin.user_management')
            ->with('success', 'User updated successfully');
    }
}
