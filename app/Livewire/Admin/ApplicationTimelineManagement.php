<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\ApplicationTimelineService;
use App\Traits\WebsiteManagementTrait;
use Illuminate\Validation\ValidationException;
use Exception;

class ApplicationTimelineManagement extends Component
{
    use WithPagination, WebsiteManagementTrait;

    protected $paginationTheme = 'tailwind';

    // Component Properties
    public $showModal = false;
    public $showDeleteModal = false;
    public $editingTimeline = null;
    public $timelineToDelete = null;
    public $form = [
        'title' => '',
        'description' => '',
        'phase' => '',
        'start_date' => '',
        'end_date' => '',
        'is_active' => true,
        'sort_order' => 0
    ];

    // Search and Filter
    public $search = '';
    public $statusFilter = '';
    public $sortBy = 'sort_order';
    public $sortDirection = 'asc';
    public $perPage = 15;

    protected $timelineService;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortBy' => ['except' => 'sort_order'],
        'sortDirection' => ['except' => 'asc'],
        'page' => ['except' => 1]
    ];

    public function boot(ApplicationTimelineService $timelineService)
    {
        $this->timelineService = $timelineService;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
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
        $this->sortBy = 'sort_order';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    public function openModal($timelineId = null)
    {
        if ($timelineId) {
            $timeline = $this->timelineService->findById($timelineId);
            if ($timeline) {
                $this->editingTimeline = $timeline->toArray();
                $this->form = [
                    'title' => $timeline->title,
                    'description' => $timeline->description,
                    'phase' => $timeline->phase,
                    'start_date' => $timeline->start_date ? $timeline->start_date->format('Y-m-d') : '',
                    'end_date' => $timeline->end_date ? $timeline->end_date->format('Y-m-d') : '',
                    'is_active' => $timeline->is_active,
                    'sort_order' => $timeline->sort_order
                ];
            }
        } else {
            $this->editingTimeline = null;
            $this->resetForm();
        }
        
        $this->showModal = true;
        $this->clearMessages();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingTimeline = null;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function resetForm()
    {
        $this->form = [
            'title' => '',
            'description' => '',
            'phase' => '',
            'start_date' => '',
            'end_date' => '',
            'is_active' => true,
            'sort_order' => 0
        ];
    }

    public function save()
    {
        $this->clearMessages();
        $this->startProcessing();

        $this->validate([
            'form.title' => 'required|string|max:255',
            'form.description' => 'nullable|string',
            'form.phase' => 'required|string|max:255',
            'form.start_date' => 'required|date',
            'form.end_date' => 'nullable|date|after:form.start_date',
            'form.sort_order' => 'required|integer|min:0',
        ]);

        try {
            // Validate timeline dates
            $this->timelineService->validateTimelineDates($this->form);

            if ($this->editingTimeline) {
                $this->timelineService->updateTimeline(
                    $this->editingTimeline['id'],
                    $this->form
                );
                $message = 'Timeline updated successfully!';
            } else {
                $this->timelineService->createTimeline($this->form);
                $message = 'Timeline created successfully!';
            }

            $this->closeModal();
            $this->setSuccessMessage($message);

        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (Exception $e) {
            $this->handleException($e, 'save timeline');
        } finally {
            $this->stopProcessing();
        }
    }

    public function toggleStatus($timelineId)
    {
        $this->clearMessages();
        $this->startProcessing();

        try {
            $timeline = $this->timelineService->toggleTimelineStatus($timelineId);
            $status = $timeline->is_active ? 'activated' : 'deactivated';
            $this->setSuccessMessage("Timeline {$status} successfully!");

        } catch (Exception $e) {
            $this->handleException($e, 'update timeline status');
        } finally {
            $this->stopProcessing();
        }
    }

    public function confirmDelete($timelineId)
    {
        $this->timelineToDelete = $this->timelineService->findById($timelineId);
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->timelineToDelete = null;
    }

    public function delete()
    {
        if (!$this->timelineToDelete) {
            return;
        }

        $this->clearMessages();
        $this->startProcessing();

        try {
            $this->timelineService->delete($this->timelineToDelete->id);
            $this->showDeleteModal = false;
            $this->timelineToDelete = null;
            $this->setSuccessMessage('Timeline deleted successfully!');

        } catch (Exception $e) {
            $this->handleException($e, 'delete timeline');
        } finally {
            $this->stopProcessing();
        }
    }

    public function reorder($orderData)
    {
        $this->clearMessages();
        $this->startProcessing();

        try {
            $this->timelineService->reorderTimelines($orderData);
            $this->setSuccessMessage('Timeline items reordered successfully!');

        } catch (Exception $e) {
            $this->handleException($e, 'reorder timeline items');
        } finally {
            $this->stopProcessing();
        }
    }

    public function getCurrentPhase()
    {
        try {
            return $this->timelineService->getCurrentPhase();
        } catch (Exception $e) {
            return null;
        }
    }

    public function getUpcomingPhases()
    {
        try {
            return $this->timelineService->getUpcomingPhases(3);
        } catch (Exception $e) {
            return collect();
        }
    }

    public function getTimelineStatistics()
    {
        try {
            return $this->timelineService->getTimelineStatistics();
        } catch (Exception $e) {
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'current_phase' => 'No active phase',
                'upcoming_count' => 0
            ];
        }
    }

    public function render()
    {
        $timelines = $this->timelineService->getTimelinesPaginated(
            $this->perPage,
            $this->search,
            $this->statusFilter,
            $this->sortBy,
            $this->sortDirection
        );

        return view('livewire.admin.application-timeline-management', [
            'timelines' => $timelines,
            'currentPhase' => $this->getCurrentPhase(),
            'upcomingPhases' => $this->getUpcomingPhases(),
            'statistics' => $this->getTimelineStatistics()
        ]);
    }
}
