<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FundRequest;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;

class FundRequestManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Filter properties
    public $status = '';
    public $scholar = '';
    public $date = '';
    public $perPage = 10;

    // Modal state
    public $showDocumentModal = false;
    public $selectedRequestId = null;
    public $modalDocuments = [];
    public $modalTitle = '';
    public $loadingDocuments = false;

    // UI state
    public $loading = false;
    public $successMessage = '';
    public $errorMessage = '';

    protected $queryString = [
        'status' => ['except' => ''],
        'scholar' => ['except' => ''],
        'date' => ['except' => ''],
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
        $this->date = request()->query('date', '');
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

    public function updatedDate()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['status', 'scholar', 'date']);
        $this->resetPage();
    }

    public function openDocumentModal($requestId)
    {
        $this->selectedRequestId = $requestId;
        $this->showDocumentModal = true;
        $this->loadingDocuments = true;
        $this->modalTitle = "Documents for Request FR-{$requestId}";

        // Load documents
        $this->loadDocuments();
    }

    public function closeDocumentModal()
    {
        $this->showDocumentModal = false;
        $this->selectedRequestId = null;
        $this->modalDocuments = [];
        $this->modalTitle = '';
        $this->loadingDocuments = false;
    }

    public function loadDocuments()
    {
        if (!$this->selectedRequestId) {
            return;
        }

        try {
            $fundRequest = FundRequest::with('documents')->findOrFail($this->selectedRequestId);
            $this->modalDocuments = $fundRequest->documents->map(function ($document) {
                return [
                    'id' => $document->id,
                    'file_name' => $document->file_name,
                    'file_path' => $document->file_path,
                    'file_type' => $document->file_type ?? 'Document',
                    'file_size' => $document->file_size ?? 0,
                ];
            })->toArray();
        } catch (\Exception $e) {
            $this->modalDocuments = [];
            $this->errorMessage = 'Error loading documents. Please try again.';
        } finally {
            $this->loadingDocuments = false;
        }
    }

    public function getFilteredFundRequestsProperty()
    {
        $query = FundRequest::with(['scholarProfile.user', 'documents']);

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

        // Filter by date (month/year)
        if ($this->date) {
            $date = $this->date;
            $query->whereYear('created_at', substr($date, 0, 4))
                ->whereMonth('created_at', substr($date, 5, 2));
        }

        return $query->orderBy('created_at', 'desc')->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.admin.fund-request-management', [
            'fundRequests' => $this->filteredFundRequests,
        ]);
    }
}
