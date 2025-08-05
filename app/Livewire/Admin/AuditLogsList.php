<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class AuditLogsList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public $perPage = 10;
    public $search = '';
    public $user = '';
    public $action = '';
    public $entityType = '';
    public $entityId = '';
    public $dateFrom = '';
    public $dateTo = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'user' => ['except' => ''],
        'action' => ['except' => ''],
        'entityType' => ['except' => '', 'as' => 'model_type'],
        'entityId' => ['except' => '', 'as' => 'model_id'],
        'dateFrom' => ['except' => '', 'as' => 'date_from'],
        'dateTo' => ['except' => '', 'as' => 'date_to'],
    ];

    public function mount()
    {
        $this->user = request()->query('user', '');
        $this->action = request()->query('action', '');
        $this->entityType = request()->query('model_type', '');
        $this->entityId = request()->query('model_id', '');
        $this->dateFrom = request()->query('date_from', '');
        $this->dateTo = request()->query('date_to', '');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedUser()
    {
        $this->resetPage();
    }

    public function updatedAction()
    {
        $this->resetPage();
    }

    public function updatedEntityType()
    {
        $this->resetPage();
    }

    public function updatedEntityId()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function applyFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'user', 'action', 'entityType', 'entityId', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function setQuickFilter($type, $value)
    {
        if ($type === 'action') {
            $this->action = $value;
        } elseif ($type === 'model_type') {
            $this->entityType = $value;
        } elseif ($type === 'date_from') {
            $this->dateFrom = $value;
        }

        $this->resetPage();
    }

    public function render()
    {
        $query = AuditLog::query()
            ->with('user')
            // Exclude logs from superadmins
            ->whereHas('user', function ($query) {
                $query->where('role', '!=', 'superadmin');
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->whereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                    ->orWhere('model_type', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->user, function ($query) {
                return $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->user . '%')
                      ->orWhere('email', 'like', '%' . $this->user . '%');
                });
            })
            ->when($this->action, function ($query) {
                return $query->where('action', $this->action);
            })
            ->when($this->entityType, function ($query) {
                return $query->where('model_type', $this->entityType);
            })
            ->when($this->entityId, function ($query) {
                return $query->where('model_id', $this->entityId);
            })
            ->when($this->dateFrom, function ($query) {
                return $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                return $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->latest();

        $auditLogs = $query->paginate($this->perPage);

        // Get unique actions for filter dropdown, excluding superadmin actions
        $actions = AuditLog::whereHas('user', function ($query) {
                $query->where('role', '!=', 'superadmin');
            })
            ->select('action')
            ->distinct()
            ->pluck('action');

        // Get unique entity types for filter dropdown, excluding superadmin entity types
        $entityTypes = AuditLog::whereHas('user', function ($query) {
                $query->where('role', '!=', 'superadmin');
            })
            ->select('model_type')
            ->distinct()
            ->pluck('model_type');

        return view('livewire.admin.audit-logs-list', [
            'auditLogs' => $auditLogs,
            'actions' => $actions,
            'entityTypes' => $entityTypes
        ]);
    }
}
