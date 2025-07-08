<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ScholarProfile;
use App\Models\RequestType;
use App\Services\AuditService;
use App\Services\FileSecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    protected AuditService $auditService;
    protected FileSecurityService $fileSecurityService;

    public function __construct(AuditService $auditService, FileSecurityService $fileSecurityService)
    {
        $this->middleware('auth');
        $this->authorizeResource(Document::class, 'document', ['except' => ['scholarIndex', 'scholarCreate', 'scholarFilesJson', 'ajaxUpload', 'scholarStore', 'adminIndex', 'adminShow', 'verify', 'reject', 'download', 'view']]);
        $this->auditService = $auditService;
        $this->fileSecurityService = $fileSecurityService;
    }

    /**
     * Display a listing of the scholar's documents.
     *
     * @return \Illuminate\Http\Response
     */
    public function scholarIndex()
    {
        $user = Auth::user();
        $this->authorize('viewAny', Document::class);
        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return redirect()->route('scholar.dashboard')->with('error', 'Scholar profile not found');
        }
        $documents = Document::where('scholar_profile_id', $scholarProfile->id)
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        return view('scholar.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new document.
     *
     * @return \Illuminate\Http\Response
     */
    public function scholarCreate()
    {
        $user = Auth::user();
        $this->authorize('create', Document::class);
        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return redirect()->route('scholar.dashboard')->with('error', 'Scholar profile not found');
        }
        $categories = [
            'Registration Form',
            'Enrollment Form',
            'Grades',
            'Thesis/Dissertation',
            'Research Paper',
            'Certificate',
            'Other'
        ];
        return view('scholar.documents.create', compact('categories'));
    }

    // Method implementation is below

    /**
     * Return all of the current scholar's uploaded documents as JSON.
     */
    public function scholarFilesJson(Request $request)
    {
        $user = Auth::user();
        $this->authorize('viewAny', Document::class);
        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return response()->json(['error' => 'Scholar profile not found'], 404);
        }
        $documents = Document::where('scholar_profile_id', $scholarProfile->id)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'file_name', 'file_type', 'file_size', 'file_path']);
        return response()->json($documents);
    }

    /**
     * Handle AJAX file upload for a document and return JSON info.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxUpload(Request $request)
    {
        $user = Auth::user();
        $this->authorize('create', Document::class);
        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return response()->json(['error' => 'Scholar profile not found'], 404);
        }
        $validated = $request->validate([
            'document' => 'required|file|max:20480', // 20MB limit
        ]);

        $file = $request->file('document');

        // Enhanced security validation
        $securityValidation = $this->fileSecurityService->validateFile($file, (string)$user->id);

        if (!$securityValidation['valid']) {
            return response()->json([
                'error' => 'File validation failed: ' . implode(', ', $securityValidation['errors'])
            ], 400);
        }

        try {
            // Create secure directory
            $documentPath = 'documents/scholar/' . $scholarProfile->id;
            $this->fileSecurityService->createSecureDirectory($documentPath);

            // Use secure filename from validation
            $fileName = $securityValidation['secure_filename'];
            $filePath = $file->storeAs($documentPath, $fileName, 'public');

            // Set secure file permissions
            $this->fileSecurityService->setSecureFilePermissions($filePath);
            $document = new Document();
            $document->scholar_profile_id = $scholarProfile->id;
            $document->file_name = $fileName;
            $document->file_path = $filePath;
            $document->file_type = $securityValidation['file_info']['mime_type'];
            $document->file_size = $securityValidation['file_info']['size'];
            $document->category = 'Fund Request';
            $document->title = pathinfo($securityValidation['file_info']['original_name'], PATHINFO_FILENAME);
            $document->description = null;
            $document->file_hash = $securityValidation['file_info']['hash_sha256'];
            $document->security_scanned = true;
            $document->security_scan_result = 'passed';
            $document->save();
            return response()->json([
                'id' => $document->id,
                'name' => $document->file_name,
                'url' => asset('storage/' . $filePath),
                'type' => $document->file_type,
                'size' => $document->file_size,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload document: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created document.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function scholarStore(Request $request)
    {
        $user = Auth::user();
        $this->authorize('create', Document::class);
        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return redirect()->route('scholar.dashboard')->with('error', 'Scholar profile not found');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'document' => 'required|file|max:20480', // 20MB limit
        ]);

        $file = $request->file('document');

        // Enhanced security validation
        $securityValidation = $this->fileSecurityService->validateFile($file, (string)$user->id);

        if (!$securityValidation['valid']) {
            return redirect()->back()
                ->with('error', 'File validation failed: ' . implode(', ', $securityValidation['errors']))
                ->withInput();
        }

        try {
            // Create secure directory
            $documentPath = 'documents/scholar/' . $scholarProfile->id;
            $this->fileSecurityService->createSecureDirectory($documentPath);

            // Use secure filename from validation
            $fileName = $securityValidation['secure_filename'];
            $filePath = $file->storeAs($documentPath, $fileName, 'public');

            // Set secure file permissions
            $this->fileSecurityService->setSecureFilePermissions($filePath);

            // Create document record with enhanced security info
            $document = new Document();
            $document->scholar_profile_id = $scholarProfile->id;
            $document->file_name = $fileName;
            $document->file_path = $filePath;
            $document->file_type = $securityValidation['file_info']['mime_type'];
            $document->file_size = $securityValidation['file_info']['size'];
            $document->category = $validated['category'];
            $document->title = $validated['title'];
            $document->description = $validated['description'] ?? null;
            $document->file_hash = $securityValidation['file_info']['hash_sha256'];
            $document->security_scanned = true;
            $document->security_scan_result = 'passed';
            $document->save();

            $this->auditService->logCreate('Document', $document->id, $document->toArray());

            return redirect()->route('scholar.documents.show', $document->id)
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
    public function scholarShow($id)
    {
        $user = Auth::user();
        $document = Document::findOrFail($id);
        $this->authorize('view', $document);
        return view('scholar.documents.show', compact('document'));
    }

    /**
     * Verify a document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verify($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $document = Document::findOrFail($id);
        $oldValues = $document->toArray();

        $document->is_verified = true;
        $document->verified_by = Auth::id();
        $document->verified_at = now();
        $document->save();

        $this->auditService->logUpdate('Document', $document->id, $oldValues, $document->toArray());

        return redirect()->back()->with('success', 'Document has been verified successfully');
    }

    /**
     * Reject a document.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $document = Document::findOrFail($id);
        $oldValues = $document->toArray();

        // Update document status
        $document->status = 'Rejected';
        $document->admin_notes = $validated['admin_notes'];
        $document->save();

        $this->auditService->logUpdate('Document', $document->id, $oldValues, $document->toArray());

        return redirect()->back()->with('success', 'Document has been rejected');
    }

    /**
     * Display the specified document for admin.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {
        $user = Auth::user();

        // Ensure user is admin
        if ($user->role !== 'admin' && $user->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }

        // For admin users, bypass any database security restrictions
        // and fetch document directly without model scopes
        $document = DB::table('documents')
            ->where('id', $id)
            ->first();

        if (!$document) {
            abort(404, 'Document not found');
        }

        // Convert to model instance for view compatibility
        $document = Document::withoutGlobalScopes()->find($id);

        return view('admin.documents.show', compact('document'));
    }

    /**
     * Display a listing of documents for admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex()
    {
        $user = Auth::user();
        $this->authorize('viewAny', Document::class);
        $documents = Document::with('scholarProfile.user')
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);

        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Download a document.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $user = Auth::user();

        // For admin users, bypass database security restrictions
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            $document = Document::withoutGlobalScopes()->find($id);
        } else {
            $document = Document::findOrFail($id);
            $this->authorize('view', $document);
        }

        if (!$document) {
            abort(404, 'Document not found');
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->download($document->file_path, $document->file_name);
        }

        return redirect()->back()->with('error', 'File not found');
    }

    /**
     * View document content inline (for PDFs, images, etc.)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $user = Auth::user();

        // For admin users, bypass database security restrictions
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            $document = Document::withoutGlobalScopes()->find($id);
        } else {
            $document = Document::findOrFail($id);
            $this->authorize('view', $document);
        }

        if (!$document) {
            abort(404, 'Document not found');
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            $filePath = Storage::disk('public')->path($document->file_path);
            $mimeType = $document->file_type ?: 'application/octet-stream';

            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $document->file_name . '"'
            ]);
        }

        return redirect()->back()->with('error', 'File not found');
    }
}
