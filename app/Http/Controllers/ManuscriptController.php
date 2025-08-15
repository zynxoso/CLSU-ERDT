<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
     * Display a listing of manuscripts for scholar users.
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

        // Return the view with Livewire component
        return view('scholar.manuscripts.index');
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
        $recentSimilar = Manuscript::withBasicRelations()
            ->where('scholar_profile_id', $scholarProfile->id)
            ->where(function ($query) use ($normalizedTitle, $normalizedAbstract) {
                $query->whereRaw('LOWER(TRIM(title)) = ?', [$normalizedTitle])
                    ->orWhereRaw('LOWER(abstract) LIKE ?', ['%' . $normalizedAbstract . '%']);
            })
            ->where('created_at', '>=', now()->subDays(30))
            ->first();

        if ($recentSimilar) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You\'ve recently submitted a similar manuscript. Please review before submitting again.');
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
        $manuscript = Manuscript::withFullRelations()->findOrFail($id);

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
        $manuscript = Manuscript::withFullRelations()->findOrFail($id);

        // Check if user is a scholar
        if ($user->role !== 'scholar') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Check if user is authorized to edit this manuscript
        if ($user->scholarProfile->id !== $manuscript->scholar_profile_id) {
            return redirect()->route('scholar.manuscripts.index')
                ->with('error', 'You are not authorized to edit this manuscript');
        }

        // Only Revision Requested manuscripts can be edited
        if (strtolower($manuscript->status) !== 'revision requested') {
            return redirect()->route('scholar.manuscripts.show', $manuscript->id)
                ->with('error', 'Only manuscripts requiring revision can be edited');
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
            $manuscript = Manuscript::withFullRelations()->findOrFail($id);

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

            // Only revision_required manuscripts can be updated
            if (strtolower($manuscript->status) !== 'revision requested') {
                \Illuminate\Support\Facades\Log::warning('Unauthorized update attempt - wrong status', [
                    'manuscript_id' => $id,
                    'current_status' => $manuscript->status
                ]);
                return redirect()->route('scholar.manuscripts.show', $manuscript->id)
                    ->with('error', 'Only manuscripts requiring revision can be updated');
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
                'file.mimes' => 'The file must be a PDF document. Please ensure you are uploading a genuine PDF file created using legitimate PDF software (not a renamed file).',
                'file.max' => 'The file size must not exceed 10MB. Please compress your PDF or reduce its size before uploading.'
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

        // Manuscripts cannot be deleted once submitted
        if ($manuscript->status === 'Submitted') {
            return redirect()->route('scholar.manuscripts.show', $manuscript->id)
                ->with('error', 'Submitted manuscripts cannot be deleted');
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
        if ($manuscript->scholar_profile_id !== Auth::user()->scholarProfile->id) {
            return redirect()->route('scholar.manuscripts.index')->with('error', 'Unauthorized access');
        }

        // Update the status to Submitted
        $oldStatus = $manuscript->status;
        $manuscript->status = 'Submitted';
        $manuscript->save();

        // Notify all admins about the new manuscript submission
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if ($admin->manuscript_notifications) {
                $admin->notify(new \App\Notifications\NewManuscriptSubmitted($manuscript));
            }
        }

        // Log the submission
        $this->auditService->logCustomAction('submitted', 'Manuscript', $manuscript->id);

        return redirect()->route('scholar.manuscripts.index')->with('success', 'Manuscript submitted successfully and is now final.');
    }
}
