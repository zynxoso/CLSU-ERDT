<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AnnouncementService;
use App\Services\FacultyService;
use App\Services\DownloadableFormService;
use App\Services\ApplicationTimelineService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class ContentManagementController extends Controller
{
    protected $announcementService;
    protected $facultyService;
    protected $downloadableFormService;
    protected $applicationTimelineService;

    public function __construct(
        AnnouncementService $announcementService,
        FacultyService $facultyService,
        DownloadableFormService $downloadableFormService,
        ApplicationTimelineService $applicationTimelineService
    ) {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });

        $this->announcementService = $announcementService;
        $this->facultyService = $facultyService;
        $this->downloadableFormService = $downloadableFormService;
        $this->applicationTimelineService = $applicationTimelineService;
    }

    public function index(Request $request)
    {
        // Determine active tab based on current route
        $routeName = $request->route()->getName();
        $activeTab = 'announcements'; // default
        
        if (str_contains($routeName, 'announcements')) {
            $activeTab = 'announcements';
        } elseif (str_contains($routeName, 'faculty')) {
            $activeTab = 'faculty';
        } elseif (str_contains($routeName, 'forms')) {
            $activeTab = 'forms';
        } elseif (str_contains($routeName, 'timelines')) {
            $activeTab = 'timelines';
        } else {
            // Fallback to request parameter or default
            $activeTab = $request->get('tab', 'announcements');
        }
        
        $searchTerm = $request->get('search', '');
        $filterType = $request->get('filter_type', '');
        $filterStatus = $request->get('filter_status', '');
        $filterCategory = $request->get('filter_category', '');

        $data = [
            'activeTab' => $activeTab,
            'searchTerm' => $searchTerm,
            'filterType' => $filterType,
            'filterStatus' => $filterStatus,
            'filterCategory' => $filterCategory,
        ];

        // Get data based on active tab
        switch ($activeTab) {
            case 'announcements':
                $data['announcements'] = $this->announcementService->search($searchTerm, $filterType, $filterStatus, 10);
                $data['announcementTypes'] = $this->announcementService->getAnnouncementTypes();
                break;
            case 'faculty':
                $searchTerm = $request->get('search', '');
                $filterStatus = $request->get('filter_status', '');
                $data['facultyMembers'] = $this->facultyService->search($searchTerm, $filterStatus, 12);
                break;
            case 'forms':
                $data['downloadableForms'] = $this->downloadableFormService->search($searchTerm, $filterCategory, $filterStatus, 10);
                $data['formCategories'] = $this->downloadableFormService->getCategories();
                break;
            case 'timelines':
                $data['timelines'] = $this->applicationTimelineService->search($searchTerm, $filterStatus, 10);
                break;
        }

        // Get statistics
        $data['stats'] = [
            'announcements' => [
                'active' => $this->announcementService->getActiveCount(),
                'total' => $this->announcementService->getTotalCount(),
            ],
            'faculty' => [
                'active' => $this->facultyService->getActiveCount(),
                'total' => $this->facultyService->getTotalCount(),
            ],
            'forms' => [
                'active' => $this->downloadableFormService->getActiveCount(),
                'total' => $this->downloadableFormService->getTotalCount(),
            ],
            'timelines' => [
                'active' => $this->applicationTimelineService->getActiveCount(),
                'total' => $this->applicationTimelineService->getTotalCount(),
            ],
        ];

        // Return appropriate view based on active tab
        $viewName = 'admin.content-management.index';
        
        if ($activeTab === 'announcements' && str_contains($routeName, 'announcements')) {
            $viewName = 'admin.content-management.announcements';
        } elseif ($activeTab === 'faculty' && str_contains($routeName, 'faculty')) {
            $viewName = 'admin.content-management.faculty';
        } elseif ($activeTab === 'forms' && str_contains($routeName, 'forms')) {
            $viewName = 'admin.content-management.forms';
        } elseif ($activeTab === 'timelines' && str_contains($routeName, 'timelines')) {
            $viewName = 'admin.content-management.timeline';
        }
        
        return view($viewName, $data);
    }

    // Announcement methods
    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,urgent,event,academic',
            'priority' => 'required|string|in:low,normal,high',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
        ]);

        try {
            $this->announcementService->createAnnouncement($request->all());
            return redirect()->route('admin.content-management.index', ['tab' => 'announcements'])
                ->with('success', 'Announcement created successfully.');
        } catch (Exception $e) {
            Log::error('Error creating announcement: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create announcement.');
        }
    }

    public function updateAnnouncement(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,urgent,event,academic',
            'priority' => 'required|string|in:low,normal,high',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
        ]);

        try {
            $this->announcementService->updateAnnouncement($id, $request->all());
            return redirect()->route('admin.content-management.index', ['tab' => 'announcements'])
                ->with('success', 'Announcement updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating announcement: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update announcement.');
        }
    }

    public function destroyAnnouncement($id)
    {
        try {
            $this->announcementService->delete($id);
            return redirect()->back()->with('success', 'Announcement deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete announcement.');
        }
    }

    public function toggleAnnouncementStatus($id)
    {
        try {
            $this->announcementService->toggleAnnouncementStatus($id);
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('Error toggling announcement status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to toggle status.']);
        }
    }

    // Faculty methods
    public function storeFaculty(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $data = $request->all();
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo');
            }
            $this->facultyService->createFaculty($data, $request->file('photo'));
            return redirect()->route('admin.content-management.index', ['tab' => 'faculty'])
                ->with('success', 'Faculty member created successfully.');
        } catch (Exception $e) {
            Log::error('Error creating faculty member: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create faculty member.');
        }
    }

    public function updateFaculty(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $data = $request->all();
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo');
            }
            $this->facultyService->updateFaculty($id, $data, $request->file('photo'));
            return redirect()->route('admin.content-management.index', ['tab' => 'faculty'])
                ->with('success', 'Faculty member updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating faculty member: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update faculty member.');
        }
    }

    public function destroyFaculty($id)
    {
        try {
            $this->facultyService->delete($id);
            return redirect()->back()->with('success', 'Faculty member deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete faculty member.');
        }
    }

    public function toggleFacultyStatus($id)
    {
        try {
            $this->facultyService->toggleStatus($id);
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('Error toggling faculty status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to toggle status.']);
        }
    }

    // Form methods
    public function storeForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:application,research,disbursement,other',
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->except('file');
            $file = $request->file('file');
            $uploaderId = auth()->id();
            
            $this->downloadableFormService->createForm($data, $file, $uploaderId);
            
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Form created successfully.']);
            }
            
            return redirect()->route('admin.content-management.index', ['tab' => 'forms'])
                ->with('success', 'Form created successfully.');
        } catch (Exception $e) {
            Log::error('Error creating form: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to create form.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to create form.');
        }
    }

    public function editForm($id)
    {
        try {
            $form = $this->downloadableFormService->findById($id);
            
            if (!$form) {
                return response()->json(['error' => 'Form not found'], 404);
            }
            
            return response()->json([
                'id' => $form->id,
                'title' => $form->title,
                'description' => $form->description,
                'category' => $form->category,
                'sort_order' => $form->sort_order,
                'is_active' => $form->is_active,
                'file_path' => $form->file_path,
                'filename' => $form->filename
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching form: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch form'], 500);
        }
    }

    public function updateForm(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|in:application,research,disbursement,other',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $data = $request->except('file');
            $file = $request->hasFile('file') ? $request->file('file') : null;
            
            $this->downloadableFormService->updateForm($id, $data, $file);
            
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Form updated successfully.']);
            }
            
            return redirect()->route('admin.content-management.index', ['tab' => 'forms'])
                ->with('success', 'Form updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating form: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to update form.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to update form.');
        }
    }

    public function destroyForm(Request $request, $id)
    {
        try {
            $this->downloadableFormService->delete($id);
            
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Form deleted successfully.']);
            }
            
            return redirect()->back()->with('success', 'Form deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting form: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete form.'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to delete form.');
        }
    }

    public function toggleFormStatus($id)
    {
        try {
            $this->downloadableFormService->toggleFormStatus($id);
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('Error toggling form status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to toggle status.']);
        }
    }

    // Timeline methods
    public function storeTimeline(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phase' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $this->applicationTimelineService->createTimeline($request->all());
            return redirect()->route('admin.content-management.index', ['tab' => 'timelines'])
                ->with('success', 'Timeline created successfully.');
        } catch (Exception $e) {
            Log::error('Error creating timeline: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create timeline.');
        }
    }

    public function updateTimeline(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phase' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $this->applicationTimelineService->updateTimeline($id, $request->all());
            return redirect()->route('admin.content-management.index', ['tab' => 'timelines'])
                ->with('success', 'Timeline updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating timeline: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update timeline.');
        }
    }

    public function destroyTimeline($id)
    {
        try {
            $this->applicationTimelineService->delete($id);
            return redirect()->route('admin.content-management.index', ['tab' => 'timelines'])
                ->with('success', 'Timeline deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting timeline: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete timeline.');
        }
    }

    public function toggleTimelineStatus($id)
    {
        try {
            $this->applicationTimelineService->toggleTimelineStatus($id);
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('Error toggling timeline status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to toggle status.']);
        }
    }
}