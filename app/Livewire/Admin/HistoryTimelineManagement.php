<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HistoryTimelineItem;
use App\Services\AuditService;

class HistoryTimelineManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $statusFilter = '';
    public $categoryFilter = '';
    public $sortBy = 'sort_order';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Modal properties
    public $showDeleteModal = false;
    public $timelineToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
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
        $this->categoryFilter = request()->query('categoryFilter', '');
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

    public function updatedCategoryFilter()
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
        $this->reset(['search', 'statusFilter', 'categoryFilter']);
        $this->resetPage();
    }

    public function toggleStatus($timelineId)
    {
        $timeline = HistoryTimelineItem::findOrFail($timelineId);
        $timeline->update([
            'is_active' => !$timeline->is_active
        ]);

        $status = $timeline->is_active ? 'activated' : 'deactivated';

        $this->auditService->log('history_timeline_status_changed', 'History Timeline', $timelineId,
            'Status changed for history timeline item: ' . $timeline->title . ' - ' . $status);

        session()->flash('success', "History timeline item {$status} successfully.");
    }

    public function confirmDelete($timelineId)
    {
        $this->timelineToDelete = HistoryTimelineItem::findOrFail($timelineId);
        $this->showDeleteModal = true;
    }

    public function deleteTimeline()
    {
        if (!$this->timelineToDelete) {
            return;
        }

        $timeline = $this->timelineToDelete;
        $title = $timeline->title;

        // Delete associated image
        if ($timeline->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($timeline->image_path);
        }

        $this->auditService->log('history_timeline_deleted', 'History Timeline', $timeline->id,
            'Deleted history timeline item: ' . $title);

        $timeline->delete();

        session()->flash('success', 'History timeline item deleted successfully.');
        $this->closeDeleteModal();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->timelineToDelete = null;
    }

    public function render()
    {
        $query = HistoryTimelineItem::query();

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('year_label', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        // Apply category filter
        if ($this->categoryFilter !== '') {
            $query->where('category', $this->categoryFilter);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $timelines = $query->paginate($this->perPage);

        return view('livewire.admin.history-timeline-management', [
            'timelines' => $timelines,
        ]);
    }
}
