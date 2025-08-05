<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Manuscript;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;

class ManuscriptManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Filter properties
    public $status = '';
    public $scholar = '';
    public $search = '';
    public $submissionDateFrom = '';
    public $submissionDateTo = '';
    public $type = '';
    public $perPage = 10;

    // Modal state and download filters
    public $showBatchDownloadModal = false;
    public $downloadStatus = '';
    public $downloadScholar = '';
    public $downloadSearch = '';
    public $downloadSubmissionDateFrom = '';
    public $downloadSubmissionDateTo = '';
    public $downloadType = '';

    // UI state
    public $loading = false;
    public $successMessage = '';
    public $errorMessage = '';

    protected $queryString = [
        'status' => ['except' => ''],
        'scholar' => ['except' => ''],
        'search' => ['except' => ''],
        'submissionDateFrom' => ['except' => ''],
        'submissionDateTo' => ['except' => ''],
        'type' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $auditService;

    public function boot(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function mount()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        // Initialize filters from query parameters
        $this->status = request()->query('status', '');
        $this->scholar = request()->query('scholar', '');
        $this->search = request()->query('search', '');
        $this->submissionDateFrom = request()->query('submission_date_from', '');
        $this->submissionDateTo = request()->query('submission_date_to', '');
        $this->type = request()->query('type', '');
    }

    // Reset page when filters change
    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedScholar()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSubmissionDateFrom()
    {
        $this->resetPage();
    }

    public function updatedSubmissionDateTo()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['status', 'scholar', 'search', 'submissionDateFrom', 'submissionDateTo', 'type']);
        $this->resetPage();
    }

    // Batch download modal methods
    public function openBatchDownloadModal()
    {
        try {
            // Initialize download filters with current filters
            $this->downloadStatus = $this->status;
            $this->downloadScholar = $this->scholar;
            $this->downloadSearch = $this->search;
            $this->downloadSubmissionDateFrom = $this->submissionDateFrom;
            $this->downloadSubmissionDateTo = $this->submissionDateTo;
            $this->downloadType = $this->type;

            $this->showBatchDownloadModal = true;

            // Clear any existing messages
            $this->successMessage = '';
            $this->errorMessage = '';
        } catch (\Exception $e) {
            $this->errorMessage = 'Error opening download modal: ' . $e->getMessage();
        }
    }

    public function closeBatchDownloadModal()
    {
        $this->showBatchDownloadModal = false;
    }

    public function resetDownloadFilters()
    {
        $this->reset([
            'downloadStatus',
            'downloadScholar',
            'downloadSearch',
            'downloadSubmissionDateFrom',
            'downloadSubmissionDateTo',
            'downloadType'
        ]);
    }

    public function applyCurrentFiltersToDownload()
    {
        $this->downloadStatus = $this->status;
        $this->downloadScholar = $this->scholar;
        $this->downloadSearch = $this->search;
        $this->downloadSubmissionDateFrom = $this->submissionDateFrom;
        $this->downloadSubmissionDateTo = $this->submissionDateTo;
        $this->downloadType = $this->type;
    }

    public function proceedWithBatchDownload()
    {
        try {
            $params = [];

            if ($this->downloadStatus) $params['status'] = $this->downloadStatus;
            if ($this->downloadScholar) $params['scholar'] = $this->downloadScholar;
            if ($this->downloadSearch) $params['search'] = $this->downloadSearch;
            if ($this->downloadSubmissionDateFrom) $params['submission_date_from'] = $this->downloadSubmissionDateFrom;
            if ($this->downloadSubmissionDateTo) $params['submission_date_to'] = $this->downloadSubmissionDateTo;
            if ($this->downloadType) $params['type'] = $this->downloadType;

            $params['format'] = 'zip';

            $downloadUrl = route('admin.manuscripts.export', $params);

            // Close modal first
            $this->showBatchDownloadModal = false;

            // Show success message
            $this->successMessage = 'Download started! Your file will be prepared and downloaded shortly.';

            // Use JavaScript to trigger download (more reliable for file downloads)
            $this->dispatch('triggerDownload', downloadUrl: $downloadUrl);
        } catch (\Exception $e) {
            $this->errorMessage = 'Error starting download: ' . $e->getMessage();
            $this->showBatchDownloadModal = false;
        }
    }

    public function proceedWithBatchDownloadAlternative()
    {
        $params = [];

        if ($this->downloadStatus) $params['status'] = $this->downloadStatus;
        if ($this->downloadScholar) $params['scholar'] = $this->downloadScholar;
        if ($this->downloadSearch) $params['search'] = $this->downloadSearch;
        if ($this->downloadSubmissionDateFrom) $params['submission_date_from'] = $this->downloadSubmissionDateFrom;
        if ($this->downloadSubmissionDateTo) $params['submission_date_to'] = $this->downloadSubmissionDateTo;
        if ($this->downloadType) $params['type'] = $this->downloadType;

        $params['format'] = 'zip';

        $downloadUrl = route('admin.manuscripts.export', $params);

        // Close modal and use Livewire redirect as fallback
        $this->showBatchDownloadModal = false;
        $this->redirect($downloadUrl);
    }

    public function getDownloadPreviewCountProperty()
    {
        $query = Manuscript::with(['scholarProfile.user', 'documents']);

        // Apply download filters
        if ($this->downloadStatus) {
            $query->where('status', $this->downloadStatus);
        }

        if ($this->downloadScholar) {
            $query->whereHas('scholarProfile.user', function($q) {
                $q->where('name', 'like', '%' . $this->downloadScholar . '%');
            });
        }

        if ($this->downloadSearch) {
            $query->where('title', 'like', '%' . $this->downloadSearch . '%');
        }

        if ($this->downloadSubmissionDateFrom) {
            $query->where('created_at', '>=', $this->downloadSubmissionDateFrom);
        }

        if ($this->downloadSubmissionDateTo) {
            $query->where('created_at', '<=', $this->downloadSubmissionDateTo . ' 23:59:59');
        }

        if ($this->downloadType) {
            $query->where('manuscript_type', $this->downloadType);
        }

        return $query->count();
    }

    public function getScholarsProperty()
    {
        // Eager load relationships to avoid N+1 issues
        return User::where('role', 'scholar')
            ->whereHas('scholarProfile.manuscripts')
            ->orderBy('name')
            ->get();
    }

    public function getFilteredManuscriptsProperty()
    {
        $query = Manuscript::with(['scholarProfile.user', 'documents']);

        // Filter by status
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Filter by scholar/author name
        if ($this->scholar) {
            $query->whereHas('scholarProfile.user', function($q) {
                $q->where('name', 'like', '%' . $this->scholar . '%');
            });
        }

        // Filter by title search
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // Filter by submission date range
        if ($this->submissionDateFrom) {
            $query->where('created_at', '>=', $this->submissionDateFrom);
        }

        if ($this->submissionDateTo) {
            $query->where('created_at', '<=', $this->submissionDateTo . ' 23:59:59');
        }

        // Filter by manuscript type
        if ($this->type) {
            $query->where('manuscript_type', $this->type);
        }

        return $query->orderBy('updated_at', 'desc')->paginate($this->perPage);
    }

    public function getExportUrlProperty()
    {
        $params = [];

        if ($this->status) $params['status'] = $this->status;
        if ($this->scholar) $params['scholar'] = $this->scholar;
        if ($this->search) $params['search'] = $this->search;
        if ($this->submissionDateFrom) $params['submission_date_from'] = $this->submissionDateFrom;
        if ($this->submissionDateTo) $params['submission_date_to'] = $this->submissionDateTo;
        if ($this->type) $params['type'] = $this->type;

        return route('admin.manuscripts.export', $params);
    }

    public function getBatchDownloadUrlProperty()
    {
        $params = [];

        if ($this->status) $params['status'] = $this->status;
        if ($this->scholar) $params['scholar'] = $this->scholar;
        if ($this->search) $params['search'] = $this->search;
        if ($this->submissionDateFrom) $params['submission_date_from'] = $this->submissionDateFrom;
        if ($this->submissionDateTo) $params['submission_date_to'] = $this->submissionDateTo;
        if ($this->type) $params['type'] = $this->type;

        $params['format'] = 'zip';

        return route('admin.manuscripts.export', $params);
    }

    public function render()
    {
        return view('livewire.admin.manuscript-management', [
            'manuscripts' => $this->filteredManuscripts,
            'exportUrl' => $this->getExportUrlProperty(),
            'batchDownloadUrl' => $this->getBatchDownloadUrlProperty(),
            'scholars' => $this->getScholarsProperty(),
        ]);
    }
}
