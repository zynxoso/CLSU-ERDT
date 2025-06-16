<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\Manuscript;
use App\Models\Document;
use App\Services\AuditService;

class ManuscriptController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->middleware('auth');
        $this->auditService = $auditService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Manuscript::query();

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by manuscript type if provided
        if ($request->has('type') && $request->type) {
            $query->where('manuscript_type', $request->type);
        }

        // If scholar, only show their manuscripts
        if ($user->role === 'scholar') {
            $profile = $user->scholarProfile;
            if (!$profile) {
                return redirect()->route('home')->with('error', 'Scholar profile not found');
            }
            $query->where('scholar_profile_id', $profile->id);
        }

        $manuscripts = $query->with(['scholarProfile'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('manuscripts.index', compact('manuscripts'));
    }

    /**
     * Display a listing of manuscripts for admin users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adminIndex(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')->with('error', 'Unauthorized access');
        }

        $query = Manuscript::with('scholarProfile.user');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('manuscript_type', $request->category);
        }

        // Filter by scholar if provided
        if ($request->has('scholar') && $request->scholar) {
            $query->whereHas('scholarProfile.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->scholar . '%');
            });
        }

        // Filter by title search if provided
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Order by updated_at in descending order
        $query->orderBy('updated_at', 'desc');

        // Paginate the results
        $manuscripts = $query->paginate(10);

        return view('admin.manuscripts.index', compact('manuscripts'));
    }

    /**
     * AJAX filter for manuscripts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxFilter(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = Manuscript::with('scholarProfile.user');

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('manuscript_type', $request->category);
        }

        // Filter by scholar if provided
        if ($request->has('scholar') && $request->scholar) {
            $query->whereHas('scholarProfile.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->scholar . '%');
            });
        }

        // Filter by title search if provided
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Order by updated_at in descending order
        $query->orderBy('updated_at', 'desc');

        // Paginate the results
        $manuscripts = $query->paginate(10);

        // Render the partial view
        $html = view('admin.manuscripts._manuscript_list', compact('manuscripts'))->render();

        // Return JSON response with HTML and pagination links
        return response()->json([
            'html' => $html,
            'pagination' => $manuscripts->links()->toHtml(),
        ]);
    }

    /**
     * Export manuscripts to CSV
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        $query = Manuscript::with('scholarProfile.user');

        // Apply filters if present
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('category') && $request->category) {
            $query->where('manuscript_type', $request->category);
        }

        if ($request->has('scholar') && $request->scholar) {
            $query->whereHas('scholarProfile.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->scholar . '%');
            });
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Get manuscripts ordered by updated_at
        $manuscripts = $query->orderBy('updated_at', 'desc')->get();

        // Create CSV file
        $fileName = 'manuscripts_' . date('Y-m-d_H-i-s') . '.csv';
        $tempFile = storage_path('app/public/exports/' . $fileName);

        // Ensure directory exists
        if (!file_exists(storage_path('app/public/exports'))) {
            mkdir(storage_path('app/public/exports'), 0755, true);
        }

        // Open file for writing
        $handle = fopen($tempFile, 'w');

        // Add UTF-8 BOM to help with Excel opening
        fputs($handle, "\xEF\xBB\xBF");

        // Write headers
        fputcsv($handle, [
            'Title',
            'Author',
            'Type',
            'Status',
            'Co-authors',
            'Last Updated'
        ]);

        // Write data rows
        foreach ($manuscripts as $manuscript) {
            $scholar = $manuscript->scholarProfile->user ?? null;
            fputcsv($handle, [
                $manuscript->title,
                $scholar ? $scholar->name : 'Unknown',
                $manuscript->manuscript_type,
                $manuscript->status,
                $manuscript->co_authors,
                $manuscript->updated_at->format('M d, Y')
            ]);
        }

        // Close the file
        fclose($handle);

        // Log the export
        $this->auditService->logAction(
            'Manuscript',
            null,
            'exported',
            [],
            ['file' => $fileName, 'filters' => $request->all()]
        );

        // Download file and then delete it
        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ])->deleteFileAfterSend(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        return view('admin.manuscripts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'manuscript_type' => 'required|string|in:Outline,Final',
            'co_authors' => 'nullable|string|max:255',
            'keywords' => 'required|string|max:255',
            'status' => 'required|string|in:Draft,Submitted,Under Review,Revision Requested,Accepted,Published,Rejected',
            'scholar_id' => 'required|exists:scholar_profiles,id',
            'admin_notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Normalize strings for better comparison
        $normalizedTitle = strtolower(trim($validated['title']));
        $normalizedAbstract = strtolower(trim($validated['abstract']));

        // Log input values for debugging
        \Log::info('Checking manuscript duplication', [
            'title' => $normalizedTitle,
            'abstract' => substr($normalizedAbstract, 0, 100), // log first 100 chars
            'scholar_id' => $validated['scholar_id'],
        ]);

        // Check for recent similar manuscripts by the same scholar within last 30 days
        $recentSimilar = Manuscript::where('scholar_profile_id', $validated['scholar_id'])
            ->where(function ($query) use ($normalizedTitle, $normalizedAbstract) {
                $query->whereRaw('LOWER(TRIM(title)) = ?', [$normalizedTitle])
                    ->orWhereRaw('LOWER(abstract) LIKE ?', ['%' . $normalizedAbstract . '%']);
            })
            ->where('created_at', '>=', now()->subDays(30))
            ->first();

        // Log query result for debugging
        \Log::info('Duplication check result', ['found' => $recentSimilar ? true : false, 'manuscript_id' => $recentSimilar ? $recentSimilar->id : null]);

        if ($recentSimilar) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You’ve recently submitted a similar manuscript. Please review before submitting again.');
        }

        $manuscript = new Manuscript();
        $manuscript->scholar_profile_id = $validated['scholar_id'];
        $manuscript->title = $validated['title'];
        $manuscript->abstract = $validated['abstract'];
        $manuscript->manuscript_type = $validated['manuscript_type'];
        $manuscript->co_authors = $validated['co_authors'] ?? null;
        $manuscript->keywords = $validated['keywords'];
        $manuscript->status = $validated['status'];
        $manuscript->admin_notes = $validated['admin_notes'] ?? null;
        $manuscript->save();

        // Generate reference number after saving
        $manuscript->reference_number = 'MS-' . str_pad($manuscript->id, 5, '0', STR_PAD_LEFT);
        $manuscript->save();

        // Handle file upload if present
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $path = $file->store('manuscripts', 'public');

            // Create a document entry for this file
            $document = new \App\Models\Document([
                'scholar_profile_id' => $validated['scholar_id'],
                'title' => $manuscript->title . ' - Manuscript File',
                'file_name' => $originalName, // Add the file_name field
                'file_path' => $path,
                'file_size' => $file->getSize(), // Add the file size
                'file_type' => 'manuscript',
                'category' => 'manuscript', // Add the category field
                'status' => 'verified',
            ]);

            $manuscript->documents()->save($document);
        }

        $this->auditService->logCreate('Manuscript', $manuscript->id, $manuscript->toArray());

        return redirect()->route('admin.manuscripts.show', $manuscript->id)
            ->with('success', 'Manuscript created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $manuscript = Manuscript::findOrFail($id);

        // Check if user is authorized to view this manuscript
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        return view('admin.manuscripts.show', compact('manuscript'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $manuscript = Manuscript::findOrFail($id);

        // Check if user is authorized to edit this manuscript
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.index')
                ->with('error', 'Unauthorized access');
        }

        // Allow admin to edit manuscripts in most active states, not just draft/revision
        // Consider if specific statuses should prevent editing by admin (e.g., 'Published', 'Rejected')
        // For now, allowing edit if not in a final uneditable state
        $uneditableStatuses = ['Published', 'Rejected']; // Example final states
        if (in_array($manuscript->status, $uneditableStatuses) && Auth::user()->role === 'admin') {
             // Potentially allow even these for admin, or add specific logic.
             // For now, this example keeps them uneditable via this general edit form.
             // If admins need to change 'Published' status, a dedicated action might be better.
        } else if (!in_array($manuscript->status, ['Draft', 'Revision Requested', 'Submitted', 'Under Review', 'Accepted'])) {
            // More refined check based on workflow needs
             if ($manuscript->status !== 'Draft' && $manuscript->status !== 'Revision Requested' && $manuscript->status !== 'Submitted') {
                return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', 'Manuscript in status ' . $manuscript->status . ' cannot be edited through this form.');
             }
        }

        return view('admin.manuscripts.edit', compact('manuscript'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $manuscript = Manuscript::findOrFail($id);

        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', 'Unauthorized access');
        }

        // Allow admin to update manuscripts in most active states
        // This check might need further refinement based on exact admin capabilities desired for each status
        $nonUpdatableStatusesByAdminForm = []; // e.g. ['Published', 'Rejected'] if they have separate admin actions
        if (in_array($manuscript->status, $nonUpdatableStatusesByAdminForm)) {
             return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', 'Manuscript in status ' . $manuscript->status . ' cannot be updated through this form.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'manuscript_type' => 'required|string|in:Outline,Final',
            'co_authors' => 'nullable|string|max:255',
            'keywords' => 'required|string|max:255',
            // Allow admin to set any valid status from the form
            'status' => 'sometimes|string|in:Draft,Submitted,Under Review,Revision Requested,Accepted,Published,Rejected'
        ]);

        $oldValues = $manuscript->toArray();

        $manuscript->title = $validated['title'];
        $manuscript->abstract = $validated['abstract'];
        $manuscript->manuscript_type = $validated['manuscript_type'];
        $manuscript->co_authors = $validated['co_authors'];
        $manuscript->keywords = $validated['keywords'];

        // Update status if it's provided and validated
        if (isset($validated['status'])) {
            $manuscript->status = $validated['status'];
        }

        $manuscript->save();

        $this->auditService->logUpdate('Manuscript', $manuscript->id, $oldValues, $manuscript->toArray());

        return redirect()->route('admin.manuscripts.show', $manuscript->id)
            ->with('success', 'Manuscript updated successfully');
    }

    /**
     * Submit the manuscript for review.
     */
    public function submit($id)
    {
        $user = Auth::user();
        $manuscript = Manuscript::findOrFail($id);

        // Check if user is authorized to submit this manuscript
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', 'Unauthorized access');
        }

        // Only draft or revision_required manuscripts can be submitted
        if (!in_array($manuscript->status, ['Draft', 'Revision Requested'])) {
            return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', 'Only draft or manuscripts requiring revision can be submitted');
        }

        $oldValues = $manuscript->toArray();

        $manuscript->status = 'Submitted';
        $manuscript->save();

        $this->auditService->logCustomAction('submitted', 'Manuscript', $manuscript->id);

        return redirect()->route('admin.manuscripts.show', $manuscript->id)
            ->with('success', 'Manuscript submitted successfully');
    }

    /**
     * Display a listing of manuscripts for scholar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function scholarIndex(Request $request)
    {
        // Check if user is a scholar
        $user = Auth::user();
        if ($user->role !== 'scholar') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Get scholar profile
        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return redirect()->route('scholar.dashboard')->with('error', 'Scholar profile not found');
        }

        $query = Manuscript::where('scholar_profile_id', $scholarProfile->id);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by manuscript type if provided
        if ($request->has('type') && $request->type) {
            $query->where('manuscript_type', $request->type);
        }

        $manuscripts = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('scholar.manuscripts.index', compact('manuscripts'));
    }

    /**
     * Show the form for creating a new manuscript for scholar.
     *
     * @return \Illuminate\Http\Response
     */
    public function scholarCreate()
    {
        // Check if user is a scholar
        $user = Auth::user();
        if ($user->role !== 'scholar') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Get scholar profile
        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return redirect()->route('scholar.dashboard')->with('error', 'Scholar profile not found');
        }

        return view('scholar.manuscripts.create');
    }

    /**
     * Store a newly created manuscript in storage for scholar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function scholarStore(Request $request)
    {
        // Check if user is a scholar
        $user = Auth::user();
        if ($user->role !== 'scholar') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Get scholar profile
        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return redirect()->route('scholar.dashboard')->with('error', 'Scholar profile not found');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'manuscript_type' => 'required|string|in:Outline,Final',
            'co_authors' => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:10240'
        ], [
            'title.required' => 'The manuscript title is required.',
            'title.max' => 'The manuscript title must not exceed 255 characters.',
            'abstract.required' => 'The abstract is required.',
            'manuscript_type.required' => 'Please select a manuscript type.',
            'manuscript_type.in' => 'The selected manuscript type is invalid.',
            'co_authors.max' => 'The co-authors field must not exceed 255 characters.',
            'keywords.max' => 'The keywords field must not exceed 255 characters.',
            'file.file' => 'The uploaded file is invalid.',
            'file.mimes' => 'The file must be a PDF document.',
            'file.max' => 'The file size must not exceed 10MB.'
        ]);

        // Normalize strings for better comparison
        $normalizedTitle = strtolower(trim($validated['title']));
        $normalizedAbstract = strtolower(trim($validated['abstract']));

        // Check for recent similar manuscripts by the same scholar within last 30 days
        $recentSimilar = Manuscript::where('scholar_profile_id', $scholarProfile->id)
            ->where(function ($query) use ($normalizedTitle, $normalizedAbstract) {
                $query->whereRaw('LOWER(TRIM(title)) = ?', [$normalizedTitle])
                    ->orWhereRaw('LOWER(abstract) LIKE ?', ['%' . $normalizedAbstract . '%']);
            })
            ->where('created_at', '>=', now()->subDays(30))
            ->first();

        if ($recentSimilar) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You’ve recently submitted a similar manuscript. Please review before submitting again.');
        }

        $manuscript = new Manuscript();
        $manuscript->scholar_profile_id = $scholarProfile->id;
        $manuscript->title = $validated['title'];
        $manuscript->abstract = $validated['abstract'];
        $manuscript->manuscript_type = $validated['manuscript_type'];
        $manuscript->co_authors = $validated['co_authors'];
        $manuscript->keywords = $validated['keywords'] ?? null;
        $manuscript->status = 'Submitted';
        $manuscript->save();

        // Generate reference number after saving
        $manuscript->reference_number = 'MS-' . str_pad($manuscript->id, 5, '0', STR_PAD_LEFT);
        $manuscript->save();

        // Handle file upload if provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . $originalName;
            $filePath = $file->storeAs('manuscripts', $fileName, 'public');

            // Create document record
            $document = new \App\Models\Document([
                'scholar_profile_id' => $scholarProfile->id,
                'title' => $originalName,
                'file_name' => $originalName,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'file_type' => 'manuscript',
                'category' => 'manuscript',
                'entity_type' => 'manuscript',
                'entity_id' => $manuscript->id,
                'uploaded_by' => $user->id,
                'status' => 'Pending',
                'description' => 'Manuscript file: ' . $validated['title'],
            ]);

            $manuscript->documents()->save($document);
        }

        $this->auditService->logCreate('Manuscript', $manuscript->id, $manuscript->toArray());

        return redirect()->route('scholar.manuscripts.show', $manuscript->id)
            ->with('success', 'Manuscript created successfully');
    }

    /**
     * Display the specified manuscript for scholar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scholarShow($id)
    {
        $user = Auth::user();
        $manuscript = Manuscript::findOrFail($id);

        // Check if user is a scholar
        if ($user->role !== 'scholar') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Check if user is authorized to view this manuscript
        if ($user->scholarProfile->id !== $manuscript->scholar_profile_id) {
            return redirect()->route('scholar.manuscripts.index')
                ->with('error', 'You are not authorized to view this manuscript');
        }

        return view('scholar.manuscripts.show', compact('manuscript'));
    }

    /**
     * Show the form for editing the specified manuscript for scholar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scholarEdit($id)
    {
        $user = Auth::user();
        $manuscript = Manuscript::findOrFail($id);

        // Check if user is a scholar
        if ($user->role !== 'scholar') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Check if user is authorized to edit this manuscript
        if ($user->scholarProfile->id !== $manuscript->scholar_profile_id) {
            return redirect()->route('scholar.manuscripts.index')
                ->with('error', 'You are not authorized to edit this manuscript');
        }

        // Only Draft or Revision Requested manuscripts can be edited
        if (!in_array(strtolower($manuscript->status), ['draft', 'revision requested'])) {
            return redirect()->route('scholar.manuscripts.show', $manuscript->id)
                ->with('error', 'Only draft or manuscripts requiring revision can be edited');
        }

        return view('scholar.manuscripts.edit', compact('manuscript'));
    }

    /**
     * Update the specified manuscript in storage for scholar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scholarUpdate(Request $request, $id)
    {
        try {
            \Illuminate\Support\Facades\Log::info('Starting manuscript update', [
                'manuscript_id' => $id,
                'request_data' => $request->except(['file', '_token', '_method']),
                'user_id' => Auth::id()
            ]);

            $user = Auth::user();
            $manuscript = Manuscript::findOrFail($id);

            // Check if user is a scholar
            if ($user->role !== 'scholar') {
                \Illuminate\Support\Facades\Log::warning('Unauthorized update attempt - not a scholar', ['user_id' => $user->id]);
                return redirect()->route('home')->with('error', 'Unauthorized access');
            }

            // Check if user is authorized to update this manuscript
            if ($user->scholarProfile->id !== $manuscript->scholar_profile_id) {
                \Illuminate\Support\Facades\Log::warning('Unauthorized update attempt - not owner', [
                    'user_id' => $user->id,
                    'scholar_profile_id' => $user->scholarProfile->id,
                    'manuscript_owner_id' => $manuscript->scholar_profile_id
                ]);
                return redirect()->route('scholar.manuscripts.index')
                    ->with('error', 'You are not authorized to update this manuscript');
            }

            // Only draft or revision_required manuscripts can be updated
            if (!in_array(strtolower($manuscript->status), ['draft', 'revision requested'])) {
                \Illuminate\Support\Facades\Log::warning('Unauthorized update attempt - wrong status', [
                    'manuscript_id' => $id,
                    'current_status' => $manuscript->status
                ]);
                return redirect()->route('scholar.manuscripts.show', $manuscript->id)
                    ->with('error', 'Only draft or manuscripts requiring revision can be updated');
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'abstract' => 'required|string',
                'manuscript_type' => 'required|string|in:Outline,Final',
                'co_authors' => 'nullable|string|max:255',
                'keywords' => 'nullable|string|max:255',
                'file' => 'nullable|file|mimes:pdf|max:10240'
            ], [
                'title.required' => 'The manuscript title is required.',
                'title.max' => 'The manuscript title must not exceed 255 characters.',
                'abstract.required' => 'The abstract is required.',
                'manuscript_type.required' => 'Please select a manuscript type.',
                'manuscript_type.in' => 'The selected manuscript type is invalid.',
                'co_authors.max' => 'The co-authors field must not exceed 255 characters.',
                'keywords.max' => 'The keywords field must not exceed 255 characters.',
                'file.file' => 'The uploaded file is invalid.',
                'file.mimes' => 'The file must be a PDF document.',
                'file.max' => 'The file size must not exceed 10MB.'
            ]);

            \Illuminate\Support\Facades\Log::info('Manuscript validation passed', [
                'manuscript_id' => $id,
                'validated_data' => $validated
            ]);

            $oldValues = $manuscript->toArray();

            $manuscript->title = $validated['title'];
            $manuscript->abstract = $validated['abstract'];
            $manuscript->manuscript_type = $validated['manuscript_type'];
            $manuscript->co_authors = $validated['co_authors'];
            $manuscript->keywords = $validated['keywords'] ?? null;

            \Illuminate\Support\Facades\Log::info('Saving manuscript', [
                'manuscript_id' => $id,
                'manuscript_data' => $manuscript->toArray()
            ]);

            $manuscript->save();

            // Handle file upload if provided
            if ($request->hasFile('file')) {
                \Illuminate\Support\Facades\Log::info('Processing file upload', [
                    'manuscript_id' => $id,
                    'filename' => $request->file('file')->getClientOriginalName()
                ]);

                $file = $request->file('file');
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                $filePath = $file->storeAs('manuscripts', $fileName, 'public');

                // Create document record
                $document = new \App\Models\Document();
                $document->scholar_profile_id = $user->scholarProfile->id; // Add scholar_profile_id
                $document->title = $originalName;
                $document->file_name = $originalName; // Add file_name
                $document->file_path = $filePath;
                $document->file_size = $file->getSize(); // Add the file size
                $document->file_type = 'manuscript';
                $document->category = 'manuscript'; // Add the category field
                $document->entity_type = 'manuscript';
                $document->entity_id = $manuscript->id;
                $document->uploaded_by = $user->id;
                $document->status = 'Pending';
                $document->description = 'Updated manuscript file: ' . $validated['title'];
                $document->save();

                \Illuminate\Support\Facades\Log::info('File uploaded and document created', [
                    'document_id' => $document->id
                ]);
            }

            $this->auditService->logUpdate('Manuscript', $manuscript->id, $oldValues, $manuscript->toArray());

            \Illuminate\Support\Facades\Log::info('Manuscript update completed successfully', [
                'manuscript_id' => $id
            ]);

            return redirect()->route('scholar.manuscripts.show', $manuscript->id)
                ->with('success', 'Manuscript updated successfully');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating manuscript', [
                'manuscript_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the manuscript: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified manuscript from storage for scholar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scholarDestroy($id)
    {
        $user = Auth::user();
        $manuscript = Manuscript::findOrFail($id);

        // Check if user is a scholar
        if ($user->role !== 'scholar') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Check if user is authorized to delete this manuscript
        if ($user->scholarProfile->id !== $manuscript->scholar_profile_id) {
            return redirect()->route('scholar.manuscripts.index')
                ->with('error', 'You are not authorized to delete this manuscript');
        }

        // Only draft manuscripts can be deleted
        if ($manuscript->status !== 'Draft') {
            return redirect()->route('scholar.manuscripts.show', $manuscript->id)
                ->with('error', 'Only draft manuscripts can be deleted');
        }

        $oldValues = $manuscript->toArray();
        $manuscript->delete();

        $this->auditService->logDelete('Manuscript', $id, $oldValues);

        return redirect()->route('scholar.manuscripts.index')
            ->with('success', 'Manuscript deleted successfully');
    }

    /**
     * Submit the manuscript for review for scholar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scholarSubmit($id)
    {
        $manuscript = Manuscript::findOrFail($id);

        // Check if the authenticated user owns the manuscript
        if ($manuscript->scholar_profile_id !== auth()->user()->scholarProfile->id) {
            return redirect()->route('scholar.manuscripts.index')->with('error', 'Unauthorized access');
        }

        // Update the status to Submitted
        $manuscript->status = 'Submitted';
        $manuscript->save();

        return redirect()->route('scholar.manuscripts.index')->with('success', 'Manuscript submitted successfully and is now final.');
    }

    /**
     * Approve a manuscript.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $manuscript = Manuscript::findOrFail($id);

        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', 'Unauthorized access');
        }

        // Only Draft or Revision Requested manuscripts can be approved
        if (!in_array($manuscript->status, ['Draft', 'Revision Requested'])) {
            return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', 'Only draft or manuscripts requiring revision can be approved');
        }

        $oldValues = $manuscript->toArray();

        $manuscript->status = 'Accepted';
        $manuscript->save();

        $this->auditService->logCustomAction('approved', 'Manuscript', $manuscript->id);

        return redirect()->route('admin.manuscripts.show', $manuscript->id)
            ->with('success', 'Manuscript approved successfully');
    }
    
    /**
     * Update the status of a manuscript.
     *
     * @param  int  $id
     * @param  string  $status
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($id, $status)
    {
        $manuscript = Manuscript::findOrFail($id);

        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', 'Unauthorized access');
        }
        
        // Validate the status
        $validStatuses = ['Draft', 'Submitted', 'Under Review', 'Revision Requested', 'Accepted', 'Published', 'Rejected'];
        if (!in_array($status, $validStatuses)) {
            return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', 'Invalid status');
        }
        
        // Check if the status transition is valid
        $validTransitions = [
            'Submitted' => ['Under Review', 'Accepted', 'Rejected'],
            'Under Review' => ['Revision Requested', 'Accepted', 'Rejected'],
            'Revision Requested' => ['Under Review', 'Accepted', 'Rejected'],
            'Accepted' => ['Published'],
        ];
        
        if (!isset($validTransitions[$manuscript->status]) || !in_array($status, $validTransitions[$manuscript->status])) {
            return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', "Cannot change status from '{$manuscript->status}' to '{$status}'");
        }

        $oldValues = $manuscript->toArray();
        $oldStatus = $manuscript->status;

        $manuscript->status = $status;
        $manuscript->save();

        $this->auditService->logCustomAction("status_change_from_{$oldStatus}_to_{$status}", 'Manuscript', $manuscript->id);

        return redirect()->route('admin.manuscripts.show', $manuscript->id)
            ->with('success', "Manuscript status updated to '{$status}' successfully");
    }
    
    /**
     * Update just the status and notes of a manuscript and notify the scholar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatusAndNotes(Request $request, $id)
    {
        $manuscript = Manuscript::findOrFail($id);

        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.manuscripts.show', $manuscript->id)
                ->with('error', 'Unauthorized access');
        }
        
        $validated = $request->validate([
            'status' => 'required|string|in:Draft,Submitted,Under Review,Revision Requested,Accepted,Published,Rejected',
            'admin_notes' => 'nullable|string',
            'notify_scholar' => 'sometimes|boolean'
        ]);
        
        $oldValues = $manuscript->toArray();
        $oldStatus = $manuscript->status;
        
        // Update manuscript status and notes
        // Ensure the status is properly quoted by using the setAttribute method
        $manuscript->setAttribute('status', $validated['status']);
        $manuscript->admin_notes = $validated['admin_notes'] ?? $manuscript->admin_notes;
        $manuscript->save();
        
        // Log the status change
        $this->auditService->logCustomAction("status_change_from_{$oldStatus}_to_{$validated['status']}", 'Manuscript', $manuscript->id);
        
        // Notify scholar if requested
        if ($request->has('notify_scholar') && $request->notify_scholar) {
            // Get the scholar user
            $scholarProfile = $manuscript->scholarProfile;
            if ($scholarProfile && $scholarProfile->user) {
                $scholarProfile->user->notify(new \App\Notifications\ManuscriptStatusChanged(
                    $manuscript,
                    $oldStatus,
                    $validated['status'],
                    $validated['admin_notes'] ?? null
                ));
            }
        }
        
        return redirect()->route('admin.manuscripts.show', $manuscript->id)
            ->with('success', "Manuscript status updated and scholar will be notified.");
    }
}
