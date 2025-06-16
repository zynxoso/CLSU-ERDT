<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScholarProfile;
use App\Models\Document;
use App\Models\FundRequest;
use App\Models\Manuscript;
use App\Models\FacultyMember;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
     * Show the system configuration page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function systemConfiguration()
    {
        return view('super_admin.system_configuration');
    }

    /**
     * Show the data management page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dataManagement()
    {
        return view('super_admin.data_management');
    }

    /**
     * Show the website management page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function websiteManagement()
    {
        $facultyMembers = FacultyMember::ordered()->get();
        $announcements = Announcement::orderByPriority()->get();
        return view('super_admin.website_management', compact('facultyMembers', 'announcements'));
    }

    /**
     * Show the application timeline management page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function applicationTimeline()
    {
        $timelines = \App\Models\ApplicationTimeline::ordered()->get();
        return view('super_admin.application_timeline', compact('timelines'));
    }

    /**
     * Show the form for creating a new timeline item.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createTimelineItem()
    {
        return view('super_admin.application_timeline_create');
    }

    /**
     * Store a newly created timeline item.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTimelineItem(Request $request)
    {
        $request->validate([
            'activity' => 'required|string|max:255',
            'first_semester' => 'required|string|max:255',
            'second_semester' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
        ]);

        \App\Models\ApplicationTimeline::create($request->all());

        $this->auditService->log('timeline_created', 'Application Timeline', null,
            'Created new timeline item: ' . $request->activity);

        return redirect()->route('super_admin.application_timeline')
            ->with('success', 'Timeline item created successfully.');
    }

    /**
     * Show the form for editing a timeline item.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editTimelineItem($id)
    {
        $timeline = \App\Models\ApplicationTimeline::findOrFail($id);
        return view('super_admin.application_timeline_edit', compact('timeline'));
    }

    /**
     * Update the specified timeline item.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTimelineItem(Request $request, $id)
    {
        $timeline = \App\Models\ApplicationTimeline::findOrFail($id);

        $request->validate([
            'activity' => 'required|string|max:255',
            'first_semester' => 'required|string|max:255',
            'second_semester' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
        ]);

        $timeline->update($request->all());

        $this->auditService->log('timeline_updated', 'Application Timeline', $id,
            'Updated timeline item: ' . $timeline->activity);

        return redirect()->route('super_admin.application_timeline')
            ->with('success', 'Timeline item updated successfully.');
    }

    /**
     * Delete the specified timeline item.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTimelineItem($id)
    {
        $timeline = \App\Models\ApplicationTimeline::findOrFail($id);
        $activity = $timeline->activity;

        $timeline->delete();

        $this->auditService->log('timeline_deleted', 'Application Timeline', $id,
            'Deleted timeline item: ' . $activity);

        return redirect()->route('super_admin.application_timeline')
            ->with('success', 'Timeline item deleted successfully.');
    }

    /**
     * Toggle the active status of a timeline item.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleTimelineStatus($id)
    {
        $timeline = \App\Models\ApplicationTimeline::findOrFail($id);
        $timeline->update([
            'is_active' => !$timeline->is_active
        ]);

        $status = $timeline->is_active ? 'activated' : 'deactivated';

        $this->auditService->log('timeline_status_changed', 'Application Timeline', $id,
            'Status changed for timeline item: ' . $timeline->activity . ' - ' . $status);

        return redirect()->route('super_admin.application_timeline')
            ->with('success', "Timeline item {$status} successfully.");
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
            $user->is_default_password = true;
            $user->must_change_password = true;
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

    /**
     * Store a newly created faculty member.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeFaculty(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'education_background' => 'required|string',
            'research_description' => 'required|string',
            'degree_level' => 'required|string|max:10',
            'university_origin' => 'nullable|string|max:255',
            'expertise_tags' => 'nullable|array',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $facultyData = $request->except(['photo']);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('experts', 'public');
            $facultyData['photo_path'] = $path;
        }

        $faculty = FacultyMember::create($facultyData);

        // Log the action
        $this->auditService->log(
            'create',
            'faculty_member',
            $faculty->id,
            'Faculty member created: ' . $faculty->name,
            $request->all()
        );

        return response()->json(['success' => true, 'message' => 'Faculty member added successfully.', 'faculty' => $faculty]);
    }

    /**
     * Update the specified faculty member.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFaculty(Request $request, $id)
    {
        $faculty = FacultyMember::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'education_background' => 'required|string',
            'research_description' => 'required|string',
            'degree_level' => 'required|string|max:10',
            'university_origin' => 'nullable|string|max:255',
            'expertise_tags' => 'nullable|array',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $facultyData = $request->except(['photo']);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($faculty->photo_path) {
                Storage::disk('public')->delete($faculty->photo_path);
            }

            $path = $request->file('photo')->store('experts', 'public');
            $facultyData['photo_path'] = $path;
        }

        $faculty->update($facultyData);

        // Log the action
        $this->auditService->log(
            'update',
            'faculty_member',
            $faculty->id,
            'Faculty member updated: ' . $faculty->name,
            $request->all()
        );

        return response()->json(['success' => true, 'message' => 'Faculty member updated successfully.', 'faculty' => $faculty]);
    }

    /**
     * Remove the specified faculty member.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFaculty($id)
    {
        $faculty = FacultyMember::findOrFail($id);

        // Delete photo if exists
        if ($faculty->photo_path) {
            Storage::disk('public')->delete($faculty->photo_path);
        }

        // Log the action before deletion
        $this->auditService->log(
            'delete',
            'faculty_member',
            $faculty->id,
            'Faculty member deleted: ' . $faculty->name,
            $faculty->toArray()
        );

        $faculty->delete();

        return response()->json(['success' => true, 'message' => 'Faculty member deleted successfully.']);
    }

    /**
     * Toggle faculty member status.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFacultyStatus($id)
    {
        $faculty = FacultyMember::findOrFail($id);
        $faculty->is_active = !$faculty->is_active;
        $faculty->save();

        // Log the action
        $this->auditService->log(
            'update',
            'faculty_member',
            $faculty->id,
            'Faculty member status toggled: ' . $faculty->name . ' (' . ($faculty->is_active ? 'activated' : 'deactivated') . ')',
            ['is_active' => $faculty->is_active]
        );

        return response()->json(['success' => true, 'message' => 'Faculty member status updated.', 'is_active' => $faculty->is_active]);
    }

    /**
     * Store a newly created announcement.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,application,scholarship,event,urgent',
            'is_active' => 'boolean',
            'priority' => 'integer|min:0|max:10',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at'
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
            'priority' => $request->integer('priority', 0),
            'published_at' => $request->published_at ?: now(),
            'expires_at' => $request->expires_at
        ]);

        $this->auditService->log('announcement_created', 'Announcement', $announcement->id,
            'Created announcement: ' . $announcement->title);

        return response()->json([
            'success' => true,
            'message' => 'Announcement created successfully.',
            'announcement' => $announcement
        ]);
    }

    /**
     * Update the specified announcement.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAnnouncement(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,application,scholarship,event,urgent',
            'is_active' => 'boolean',
            'priority' => 'integer|min:0|max:10',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at'
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
            'priority' => $request->integer('priority', 0),
            'published_at' => $request->published_at ?: $announcement->published_at,
            'expires_at' => $request->expires_at
        ]);

        $this->auditService->log('announcement_updated', 'Announcement', $announcement->id,
            'Updated announcement: ' . $announcement->title);

        return response()->json([
            'success' => true,
            'message' => 'Announcement updated successfully.',
            'announcement' => $announcement->fresh()
        ]);
    }

    /**
     * Delete the specified announcement.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $title = $announcement->title;

        $announcement->delete();

        $this->auditService->log('announcement_deleted', 'Announcement', $id,
            'Deleted announcement: ' . $title);

        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully.'
        ]);
    }

    /**
     * Toggle the active status of an announcement.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleAnnouncementStatus($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        $status = $announcement->is_active ? 'activated' : 'deactivated';
        $this->auditService->log('announcement_toggled', 'Announcement', $id,
            'Announcement ' . $status . ': ' . $announcement->title);

        return response()->json([
            'success' => true,
            'message' => 'Announcement status updated successfully.',
            'announcement' => $announcement
        ]);
    }
}
