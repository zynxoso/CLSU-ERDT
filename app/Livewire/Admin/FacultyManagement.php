<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\FacultyService;
use App\Traits\WebsiteManagementTrait;
use Illuminate\Validation\ValidationException;
use Exception;

class FacultyManagement extends Component
{
    use WithFileUploads, WebsiteManagementTrait;

    // Component Properties
    public $facultyMembers = [];
    public $showModal = false;
    public $editingFaculty = null;
    public $form = [
        'name' => '',
        'position' => '',
        'specialization' => '',
        'department' => '',
        'sort_order' => 0
    ];
    public $photo;

    // Search and Filter
    public $search = '';
    public $departmentFilter = '';

    protected $facultyService;

    public function boot(FacultyService $facultyService)
    {
        $this->facultyService = $facultyService;
    }

    public function mount()
    {
        $this->loadFacultyMembers();
    }

    public function loadFacultyMembers()
    {
        try {
            if ($this->search || $this->departmentFilter) {
                $this->facultyMembers = $this->facultyService
                    ->searchFaculty($this->search, $this->departmentFilter)
                    ->toArray();
            } else {
                $this->facultyMembers = $this->facultyService
                    ->getAllOrdered()
                    ->toArray();
            }
        } catch (Exception $e) {
            $this->handleException($e, 'load faculty members');
        }
    }

    public function updatedSearch()
    {
        $this->loadFacultyMembers();
    }

    public function updatedDepartmentFilter()
    {
        $this->loadFacultyMembers();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'departmentFilter']);
        $this->loadFacultyMembers();
    }

    public function openModal($facultyId = null)
    {
        if ($facultyId) {
            $this->editingFaculty = collect($this->facultyMembers)
                ->firstWhere('id', $facultyId);
            
            $this->form = [
                'name' => $this->editingFaculty['name'] ?? '',
                'position' => $this->editingFaculty['position'] ?? '',
                'specialization' => $this->editingFaculty['specialization'] ?? '',
                'department' => $this->editingFaculty['department'] ?? '',
                'sort_order' => $this->editingFaculty['sort_order'] ?? 0
            ];
        } else {
            $this->editingFaculty = null;
            $this->resetForm();
        }
        
        $this->showModal = true;
        $this->clearMessages();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingFaculty = null;
        $this->resetForm();
        $this->photo = null;
        $this->resetErrorBag();
    }

    public function resetForm()
    {
        $this->form = [
            'name' => '',
            'position' => '',
            'specialization' => '',
            'department' => '',
            'sort_order' => 0
        ];
    }

    public function save()
    {
        $this->clearMessages();
        $this->startProcessing();

        $this->validate([
            'form.name' => 'required|string|max:255',
            'form.position' => 'required|string|max:255',
            'form.specialization' => 'required|string|max:255',
            'form.department' => 'required|string|max:255',
            'form.sort_order' => 'required|integer|min:0',
            'photo' => 'nullable|image|max:2048'
        ]);

        try {
            if ($this->editingFaculty) {
                $this->facultyService->updateFaculty(
                    $this->editingFaculty['id'],
                    $this->form,
                    $this->photo
                );
                $message = 'Faculty member updated successfully!';
            } else {
                $this->facultyService->createFaculty($this->form, $this->photo);
                $message = 'Faculty member created successfully!';
            }

            $this->loadFacultyMembers();
            $this->closeModal();
            $this->setSuccessMessage($message);

        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (Exception $e) {
            $this->handleException($e, 'save faculty member');
        } finally {
            $this->stopProcessing();
        }
    }

    public function delete($facultyId)
    {
        $this->clearMessages();
        $this->startProcessing();

        try {
            $this->facultyService->deleteFaculty($facultyId);
            $this->loadFacultyMembers();
            $this->setSuccessMessage('Faculty member deleted successfully!');

        } catch (Exception $e) {
            $this->handleException($e, 'delete faculty member');
        } finally {
            $this->stopProcessing();
        }
    }

    public function reorder($orderData)
    {
        $this->clearMessages();
        $this->startProcessing();

        try {
            $this->facultyService->reorderFaculty($orderData);
            $this->loadFacultyMembers();
            $this->setSuccessMessage('Faculty members reordered successfully!');

        } catch (Exception $e) {
            $this->handleException($e, 'reorder faculty members');
        } finally {
            $this->stopProcessing();
        }
    }

    public function getDepartments()
    {
        try {
            return $this->facultyService->getDepartments();
        } catch (Exception $e) {
            return [];
        }
    }

    public function getPhotoUrl($faculty)
    {
        if (!isset($faculty['photo_path']) || !$faculty['photo_path']) {
            return null;
        }

        $photoPath = 'experts/' . $faculty['photo_path'];
        return file_exists(public_path($photoPath)) ? asset($photoPath) : null;
    }

    public function render()
    {
        return view('livewire.admin.faculty-management', [
            'departments' => $this->getDepartments()
        ]);
    }
}