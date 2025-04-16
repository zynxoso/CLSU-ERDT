<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\ScholarProfile;
use App\Services\AuditService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth; // Add this import

class DocumentController extends Controller
{
    protected $auditService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\AuditService  $auditService
     * @return void
     */
    public function __construct(AuditService $auditService)
    {
        $this->middleware('auth');
        $this->auditService = $auditService;
    }

    /**
     * Display a listing of the documents.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Different queries based on user role
        if (Auth::user()->role === 'scholar') {
            $scholarProfile = Auth::user()->scholarProfile;

            if (!$scholarProfile) {
                return redirect()->route('dashboard')
                    ->with('error', 'You need to complete your scholar profile first.');
            }

            $query = Document::where('scholar_profile_id', $scholarProfile->id);
        } else {
            $query = Document::with('scholarProfile');

            // Filter by scholar
            if ($request->has('scholar_id') && $request->scholar_id) {
                $query->where('scholar_profile_id', $request->scholar_id);
            }
        }

        // Common filters
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Order by created_at in descending order
        $query->orderBy('created_at', 'desc');

        // Paginate the results
        $documents = $query->paginate(20);

        // Get data for filters
        $categories = Document::distinct('category')->pluck('category');
        $statuses = Document::distinct('status')->pluck('status');

        // For admin, get scholars for filtering
        $scholars = null;
        if (Auth::user()->role !== 'scholar') {
            $scholars = ScholarProfile::orderBy('last_name')->orderBy('first_name')->get();
        }

        return view('documents.index', compact('documents', 'categories', 'statuses', 'scholars'));
    }

    /**
     * Show the form for creating a new document.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $scholarProfile = Auth::user()->scholarProfile;

        if (!$scholarProfile) {
            return redirect()->route('dashboard')
                ->with('error', 'You need to complete your scholar profile first.');
        }

        return view('documents.create', compact('scholarProfile'));
    }

    /**
     * Show the form for creating a new document for scholar.
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

        return view('scholar.documents.create');
    }

    /**
     * Store a newly created document in storage for scholar.
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

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('documents/' . $scholarProfile->id, 'public');

            $document = new Document();
            $document->scholar_profile_id = $scholarProfile->id;
            $document->title = $request->title;
            $document->file_name = $file->getClientOriginalName();
            $document->file_path = $path;
            $document->file_type = $file->getClientMimeType();
            $document->file_size = $file->getSize();
            $document->category = $request->category;
            $document->description = $request->description;
            $document->status = 'Pending';
            $document->save();

            // Log the action
            $this->auditService->logCreate('Document', $document->id, $document->toArray());

            return redirect()->route('scholar.documents.index')
                ->with('success', 'Document uploaded successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to upload document: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store a newly created document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $scholarProfile = Auth::user()->scholarProfile;

        if (!$scholarProfile) {
            return redirect()->route('dashboard')
                ->with('error', 'You need to complete your scholar profile first.');
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
            'category' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('documents/' . $scholarProfile->id, 'public');

            $document = Document::create([
                'scholar_profile_id' => $scholarProfile->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'category' => $request->category,
                'description' => $request->description,
                'status' => 'Uploaded',
            ]);

            // Log the action
            $this->auditService->logCreate('Document', $document->id, $document->toArray());

            return redirect()->route('documents.index')
                ->with('success', 'Document uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to upload document: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = Document::findOrFail($id);

        // Handle based on user role
        if (Auth::user()->role === 'scholar') {
            // Check if the scholar owns this document
            if (Auth::user()->scholarProfile->id !== $document->scholar_profile_id) {
                abort(403, 'Unauthorized action.');
            }
            return redirect()->route('scholar.documents.show', $document->id);
        } else if (Auth::user()->role === 'admin') {
            // Admin view - Check if we're already in an admin route to prevent redirect loops
            if (request()->is('admin/*')) {
                return view('admin.documents.show', compact('document'));
            }
            return redirect()->route('admin.documents.show', $document->id);
        } else {
            return view('documents.show', compact('document'));
        }
    }

    /**
     * Download the specified document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $document = Document::findOrFail($id);

        // Check if the file exists
        if (!Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Log the download
        $this->auditService->logCustomAction(
            'Downloaded',
            'Document',
            $document->id,
            [
                'user_id' => Auth::id(),
                'document_id' => $document->id,
                'file_name' => $document->file_name,
            ]
        );

        // Return the file as a download response
        return Response::download(storage_path('app/public/' . $document->file_path), $document->file_name);
    }

    /**
     * Show the form for editing the specified document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $document = Document::findOrFail($id);

        // Check if the current user is the scholar who owns this document
        if (Auth::user()->role === 'scholar' && Auth::user()->scholarProfile->id !== $document->scholar_profile_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('documents.edit', compact('document'));
    }

    /**
     * Show the form for editing the specified document for scholar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scholarEdit($id)
    {
        $user = Auth::user();
        $document = Document::findOrFail($id);

        // Check if user is a scholar
        if ($user->role !== 'scholar') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Check if the user is authorized to edit this document
        if ($user->scholarProfile->id !== $document->scholar_profile_id) {
            return redirect()->route('scholar.documents.index')
                ->with('error', 'You are not authorized to edit this document');
        }

        return view('scholar.documents.edit', compact('document'));
    }

    /**
     * Update the specified document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        // Check if the current user is the scholar who owns this document or an admin
        if (Auth::user()->role === 'scholar' && Auth::user()->scholarProfile->id !== $document->scholar_profile_id) {
            abort(403, 'Unauthorized action.');
        }

        // Different validation based on user role
        if (Auth::user()->role === 'scholar') {
            $request->validate([
                'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
                'category' => 'required|string',
                'description' => 'nullable|string',
            ]);
        } else {
            $request->validate([
                'status' => 'required|string|in:Uploaded,Verified,Rejected',
                'admin_notes' => 'nullable|string',
            ]);
        }

        try {
            // Store old values for audit log
            $oldValues = $document->toArray();

            // Update document
            if (Auth::user()->role === 'scholar') {
                $document->update([
                    'category' => $request->category,
                    'description' => $request->description,
                ]);

                // Handle file upload if provided
                if ($request->hasFile('file')) {
                    // Delete old file
                    if (Storage::disk('public')->exists($document->file_path)) {
                        Storage::disk('public')->delete($document->file_path);
                    }

                    // Upload new file
                    $file = $request->file('file');
                    $path = $file->store('documents/' . $document->scholar_profile_id, 'public');

                    $document->update([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'status' => 'Uploaded', // Reset status when file is updated
                    ]);
                }
            } else {
                // Admin update
                $document->update([
                    'status' => $request->status,
                    'admin_notes' => $request->admin_notes,
                    'verified_by' => Auth::id(),
                    'verified_at' => now(),
                ]);
            }

            // Log the action
            $this->auditService->logUpdate(
                'Document',
                $document->id,
                $oldValues,
                $document->toArray()
            );

            // Redirect based on user role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.documents.show', $document->id)
                    ->with('success', 'Document updated successfully.');
            } else {
                return redirect()->route('scholar.documents.show', $document->id)
                    ->with('success', 'Document updated successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update document: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified document from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Check if the current user is the scholar who owns this document
        if (Auth::user()->role === 'scholar' && Auth::user()->scholarProfile->id !== $document->scholar_profile_id) {
            abort(403, 'Unauthorized action.');
        }

        // Scholars can only delete their own documents that are not associated with fund requests or manuscripts
        if (Auth::user()->role === 'scholar' && ($document->fund_request_id || $document->manuscript_id)) {
            return redirect()->back()
                ->with('error', 'Cannot delete documents associated with fund requests or manuscripts.');
        }

        try {
            // Store document data for audit log
            $documentData = $document->toArray();

            // Delete file from storage
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Delete document record
            $document->delete();

            // Log the action
            $this->auditService->logDelete('Document', $id, $documentData);

            // Redirect based on user role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.documents.index')
                    ->with('success', 'Document deleted successfully.');
            } else {
                return redirect()->route('scholar.documents.index')
                    ->with('success', 'Document deleted successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete document: ' . $e->getMessage());
        }
    }

    /**
     * Verify the specified document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verify($id)
    {
        $document = Document::findOrFail($id);

        // Only admins can verify documents
        if (Auth::user()->role === 'scholar') {
            abort(403, 'Unauthorized action.');
        }

        $oldValues = $document->toArray();

        $document->status = 'Verified';
        $document->verified_by = Auth::id();
        $document->verified_at = now();
        $document->save();

        // Log the verification
        $this->auditService->logUpdate('Document', $document->id, $oldValues, $document->toArray());

        return redirect()->route('admin.documents.show', $document->id)
            ->with('success', 'Document verified successfully.');
    }

    /**
     * Reject the specified document.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        // Only admins can reject documents
        if (Auth::user()->role === 'scholar') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $document = Document::findOrFail($id);

        // Store old values for audit log
        $oldValues = $document->toArray();

        // Update document status
        $document->update([
            'status' => 'Rejected',
            'admin_notes' => $request->admin_notes,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Log the action
        $this->auditService->logUpdate(
            'Document',
            $document->id,
            $oldValues,
            $document->toArray()
        );

        return redirect()->route('admin.documents.show', $document->id)
            ->with('success', 'Document rejected successfully.');
    }

    /**
     * Display a listing of the documents for admin users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adminIndex(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $query = Document::with(['scholarProfile', 'user']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type if provided
        if ($request->has('type') && $request->type) {
            $query->where('category', $request->type);
        }

        // Filter by scholar if provided
        if ($request->has('scholar') && $request->scholar) {
            $query->whereHas('scholarProfile.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->scholar . '%');
            });
        }

        // Order by created_at in descending order
        $query->orderBy('created_at', 'desc');

        // Paginate the results
        $documents = $query->paginate(12);

        // Get counts for status cards
        $pendingCount = Document::whereIn('status', ['Pending', 'Uploaded'])->count();
        $verifiedCount = Document::where('status', 'Verified')->count();
        $rejectedCount = Document::where('status', 'Rejected')->count();

        return view('admin.documents.index', compact('documents', 'pendingCount', 'verifiedCount', 'rejectedCount'));
    }

    /**
     * Display a listing of documents for scholar.
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

        $query = Document::where('scholar_profile_id', $scholarProfile->id);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type if provided
        if ($request->has('type') && $request->type) {
            $query->where('category', $request->type);
        }

        // Filter by date if provided
        if ($request->has('date') && $request->date) {
            $date = $request->date;
            $query->whereYear('created_at', substr($date, 0, 4))
                ->whereMonth('created_at', substr($date, 5, 2));
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('scholar.documents.index', compact('documents'));
    }

    /**
     * Display the specified document for scholar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scholarShow($id)
    {
        $user = Auth::user();
        $document = Document::findOrFail($id);

        // Check if user is authorized to view this document
        if ($user->role === 'scholar' && $user->scholarProfile->id !== $document->scholar_profile_id) {
            return redirect()->route('scholar.documents.index')
                ->with('error', 'You are not authorized to view this document');
        }

        if ($user->role === 'scholar') {
            return view('scholar.documents.show', compact('document'));
        } else {
            return view('documents.show', compact('document'));
        }
    }

    /**
     * Download the specified document for scholar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scholarDownload($id)
    {
        $document = Document::findOrFail($id);
        $user = Auth::user();

        // Check if the user is authorized to download this document
        if ($user->role === 'scholar' && $user->scholarProfile->id !== $document->scholar_profile_id) {
            return redirect()->route('scholar.documents.index')
                ->with('error', 'You are not authorized to download this document');
        }

        // Check if the file exists
        if (!Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'File not found');
        }

        // Log the download
        $this->auditService->logCustomAction(
            'Downloaded',
            'Document',
            $document->id,
            [
                'user_id' => Auth::id(),
                'document_id' => $document->id,
                'file_name' => $document->file_name,
            ]
        );

        // Return the file as a download response
        return Response::download(storage_path('app/public/' . $document->file_path), $document->file_name);
    }

    /**
     * Update the specified document in storage for scholar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scholarUpdate(Request $request, $id)
    {
        $user = Auth::user();

        // Check if user is a scholar
        if ($user->role !== 'scholar') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $document = Document::findOrFail($id);

        // Check if the user is authorized to update this document
        if ($user->scholarProfile->id !== $document->scholar_profile_id) {
            return redirect()->route('scholar.documents.index')
                ->with('error', 'You are not authorized to update this document');
        }

        $request->validate([
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            // Store old values for audit log
            $oldValues = $document->toArray();

            // Update basic information
            $document->title = $request->title;
            $document->category = $request->category;
            $document->description = $request->description;

            // Handle file upload if provided
            if ($request->hasFile('file')) {
                // Delete old file if it exists
                if (Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }

                // Upload new file
                $file = $request->file('file');
                $path = $file->store('documents/' . $document->scholar_profile_id, 'public');

                $document->file_name = $file->getClientOriginalName();
                $document->file_path = $path;
                $document->file_type = $file->getClientMimeType();
                $document->file_size = $file->getSize();
                $document->status = 'Pending'; // Reset status when file is updated
            }

            $document->save();

            // Log the action
            $this->auditService->logUpdate(
                'Document',
                $document->id,
                $oldValues,
                $document->toArray()
            );

            return redirect()->route('scholar.documents.show', $document->id)
                ->with('success', 'Document updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update document: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Filter documents with AJAX request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxFilter(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = Document::with(['scholarProfile', 'user']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type if provided
        if ($request->has('type') && $request->type) {
            $query->where('category', $request->type);
        }

        // Filter by scholar if provided
        if ($request->has('scholar') && $request->scholar) {
            $query->whereHas('scholarProfile.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->scholar . '%');
            });
        }

        // Order by created_at in descending order
        $query->orderBy('created_at', 'desc');

        // Paginate the results
        $documents = $query->paginate(12);

        // Get counts for status cards
        $pendingCount = Document::whereIn('status', ['Pending', 'Uploaded'])->count();
        $verifiedCount = Document::where('status', 'Verified')->count();
        $rejectedCount = Document::where('status', 'Rejected')->count();

        // Render the documents HTML
        $html = view('admin.documents._document_list', compact('documents'))->render();

        // Render pagination links
        $pagination = $documents->links()->toHtml();

        return response()->json([
            'html' => $html,
            'pagination' => $pagination,
            'counts' => [
                'pending' => $pendingCount,
                'verified' => $verifiedCount,
                'rejected' => $rejectedCount
            ]
        ]);
    }
}
