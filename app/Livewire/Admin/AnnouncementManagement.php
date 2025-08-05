<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\AnnouncementService;
use App\Traits\WebsiteManagementTrait;
use Illuminate\Validation\ValidationException;
use Exception;

class AnnouncementManagement extends Component
{
    use WebsiteManagementTrait;

    // Component Properties
    public $announcements = [];
    public $showModal = false;
    public $editingAnnouncement = null;
    public $form = [
        'title' => '',
        'content' => '',
        'type' => 'general',
        'is_active' => true,
        'priority' => 0
    ];

    // Search and Filter
    public $search = '';
    public $typeFilter = '';
    public $statusFilter = '';

    protected $announcementService;

    public function boot(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
    }

    public function mount()
    {
        $this->loadAnnouncements();
    }

    public function loadAnnouncements()
    {
        try {
            if ($this->search || $this->typeFilter || $this->statusFilter) {
                $this->announcements = $this->announcementService
                    ->searchAnnouncements(
                        $this->search,
                        $this->typeFilter,
                        $this->statusFilter === '1'
                    )
                    ->toArray();
            } else {
                $this->announcements = $this->announcementService
                    ->getAllOrdered()
                    ->toArray();
            }
        } catch (Exception $e) {
            $this->handleException($e, 'load announcements');
        }
    }

    public function updatedSearch()
    {
        $this->loadAnnouncements();
    }

    public function updatedTypeFilter()
    {
        $this->loadAnnouncements();
    }

    public function updatedStatusFilter()
    {
        $this->loadAnnouncements();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'typeFilter', 'statusFilter']);
        $this->loadAnnouncements();
    }

    public function openModal($announcementId = null)
    {
        if ($announcementId) {
            $this->editingAnnouncement = collect($this->announcements)
                ->firstWhere('id', $announcementId);
            
            $this->form = [
                'title' => $this->editingAnnouncement['title'] ?? '',
                'content' => $this->editingAnnouncement['content'] ?? '',
                'type' => $this->editingAnnouncement['type'] ?? 'general',
                'is_active' => $this->editingAnnouncement['is_active'] ?? true,
                'priority' => $this->editingAnnouncement['priority'] ?? 0
            ];
        } else {
            $this->editingAnnouncement = null;
            $this->resetForm();
        }
        
        $this->showModal = true;
        $this->clearMessages();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingAnnouncement = null;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function resetForm()
    {
        $this->form = [
            'title' => '',
            'content' => '',
            'type' => 'general',
            'is_active' => true,
            'priority' => 0
        ];
    }

    public function save()
    {
        $this->clearMessages();
        $this->startProcessing();

        $this->validate([
            'form.title' => 'required|string|max:255',
            'form.content' => 'required|string',
            'form.type' => 'required|in:general,application,scholarship,event,urgent',
            'form.priority' => 'required|integer|min:0|max:10',
        ]);

        try {
            if ($this->editingAnnouncement) {
                $this->announcementService->updateAnnouncement(
                    $this->editingAnnouncement['id'],
                    $this->form
                );
                $message = 'Announcement updated successfully!';
            } else {
                $this->announcementService->createAnnouncement($this->form);
                $message = 'Announcement created successfully!';
            }

            $this->loadAnnouncements();
            $this->closeModal();
            $this->setSuccessMessage($message);

        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (Exception $e) {
            $this->handleException($e, 'save announcement');
        } finally {
            $this->stopProcessing();
        }
    }

    public function toggleStatus($announcementId)
    {
        $this->clearMessages();
        $this->startProcessing();

        try {
            $announcement = $this->announcementService->toggleAnnouncementStatus($announcementId);
            $status = $announcement->is_active ? 'activated' : 'deactivated';
            
            $this->loadAnnouncements();
            $this->setSuccessMessage("Announcement {$status} successfully!");

        } catch (Exception $e) {
            $this->handleException($e, 'update announcement status');
        } finally {
            $this->stopProcessing();
        }
    }

    public function delete($announcementId)
    {
        $this->clearMessages();
        $this->startProcessing();

        try {
            $this->announcementService->delete($announcementId);
            $this->loadAnnouncements();
            $this->setSuccessMessage('Announcement deleted successfully!');

        } catch (Exception $e) {
            $this->handleException($e, 'delete announcement');
        } finally {
            $this->stopProcessing();
        }
    }

    public function getAnnouncementTypes()
    {
        return $this->announcementService->getAnnouncementTypes();
    }

    public function getTypeColor($type)
    {
        $colors = [
            'general' => '#757575',
            'application' => '#1976D2',
            'scholarship' => '#388E3C',
            'event' => '#F57C00',
            'urgent' => '#D32F2F'
        ];
        
        return $colors[$type] ?? '#757575';
    }

    public function render()
    {
        return view('livewire.admin.announcement-management', [
            'announcementTypes' => $this->getAnnouncementTypes()
        ]);
    }
}