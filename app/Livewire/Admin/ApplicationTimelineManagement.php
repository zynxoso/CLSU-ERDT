<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ApplicationTimeline;
use App\Services\AuditService;

class ApplicationTimelineManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $statusFilter = '';
    public $sortBy = 'sort_order';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Modal properties
    public $showDeleteModal = false;
    public $timelineToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortBy' => ['except' => 'sort_order'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $auditService;

    public function boot(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function mount()
    {
        $this->search = request()->query('search', '');
        $this->statusFilter = request()->query('statusFilter', '');
        $this->sortBy = request()->query('sortBy', 'sort_order');
        $this->sortDirection = request()->query('sortDirection', 'asc');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter']);
        $this->resetPage();
    }

    public function toggleStatus($timelineId)
    {
        $timeline = ApplicationTimeline::findOrFail($timelineId);
        $timeline->update([
            'is_active' => !$timeline->is_active
        ]);

        $status = $timeline->is_active ? 'activated' : 'deactivated';

        $this->auditService->log('timeline_status_changed', 'Application Timeline', $timelineId,
            'Status changed for timeline item: ' . $timeline->activity . ' - ' . $status);

        session()->flash('success', "Timeline item {$status} successfully.");
    }

    public function confirmDelete($timelineId)
    {
        $this->timelineToDelete = ApplicationTimeline::findOrFail($timelineId);
        $this->showDeleteModal = true;
    }

    public function deleteTimeline()
    {
        if (!$this->timelineToDelete) {
            return;
        }

        $timeline = $this->timelineToDelete;
        $activity = $timeline->activity;

        $this->auditService->log('timeline_deleted', 'Application Timeline', $timeline->id,
            'Deleted timeline item: ' . $activity);

        $timeline->delete();

        session()->flash('success', 'Timeline item deleted successfully.');
        $this->closeDeleteModal();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->timelineToDelete = null;
    }

    public function render()
    {
        $query = ApplicationTimeline::query();

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('activity', 'like', '%' . $this->search . '%')
                  ->orWhere('first_semester', 'like', '%' . $this->search . '%')
                  ->orWhere('second_semester', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $timelines = $query->paginate($this->perPage);

        return view('livewire.admin.application-timeline-management', [
            'timelines' => $timelines,
        ]);
    }
}
