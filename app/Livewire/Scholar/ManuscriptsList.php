<?php

namespace App\Livewire\Scholar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Manuscript;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditService;

class ManuscriptsList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $status = '';
    public $manuscript_type = '';
    public $search = '';
    public $perPage = 10;

    protected $queryString = [
        'status' => ['except' => ''],
        'manuscript_type' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    protected $auditService;

    public function boot(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function mount()
    {
        $this->status = request()->query('status', '');
        $this->manuscript_type = request()->query('manuscript_type', '');
        $this->search = request()->query('search', '');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedManuscriptType()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['status', 'manuscript_type', 'search']);
        $this->resetPage();
    }

    public function submitManuscript($manuscriptId)
    {
        $user = Auth::user();

        if ($user->role !== 'scholar') {
            session()->flash('error', 'Unauthorized access');
            return;
        }

        $manuscript = Manuscript::findOrFail($manuscriptId);

        // Check if the authenticated user owns the manuscript
        if ($manuscript->scholar_profile_id !== $user->scholarProfile->id) {
            session()->flash('error', 'Unauthorized access');
            return;
        }

        // Check if manuscript can be submitted
        if ($manuscript->status !== 'Revision Requested') {
            session()->flash('error', 'Only draft or manuscripts requiring revision can be submitted');
            return;
        }

        // Update the status to Submitted
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

        session()->flash('success', 'Manuscript submitted successfully and is now final.');
    }

    public function render()
    {
        $user = Auth::user();

        if ($user->role !== 'scholar') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $scholarProfile = $user->scholarProfile;
        if (!$scholarProfile) {
            return redirect()->route('scholar.dashboard')->with('error', 'Scholar profile not found');
        }

        $manuscripts = Manuscript::withFullRelations()
            ->where('scholar_profile_id', $scholarProfile->id)
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            })
            ->when($this->manuscript_type, function ($query) {
                return $query->where('manuscript_type', $this->manuscript_type);
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('abstract', 'like', '%' . $this->search . '%')
                      ->orWhere('co_authors', 'like', '%' . $this->search . '%')
                      ->orWhere('keywords', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.scholar.manuscripts-list', [
            'manuscripts' => $manuscripts,
        ]);
    }
}
