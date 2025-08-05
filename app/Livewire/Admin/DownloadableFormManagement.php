<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Services\DownloadableFormService;
use App\Traits\WebsiteManagementTrait;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Exception;

class DownloadableFormManagement extends Component
{
    use WithFileUploads, WithPagination, WebsiteManagementTrait;

    protected $paginationTheme = 'tailwind';

    // Component Properties
    public $showModal = false;
    public $editingForm = null;
    public $form = [
        'title' => '',
        'description' => '',
        'category' => 'application',
        'status' => true,
        'sort_order' => 0
    ];
    public $file;

    // Search and Filter
    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $perPage = 15;

    protected $formService;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'page' => ['except' => 1]
    ];

    public function boot(DownloadableFormService $formService)
    {
        $this->formService = $formService;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'categoryFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function openModal($formId = null)
    {
        if ($formId) {
            $formModel = $this->formService->findById($formId);
            if ($formModel) {
                $this->editingForm = $formModel->toArray();
                $this->form = [
                    'title' => $formModel->title,
                    'description' => $formModel->description,
                    'category' => $formModel->category,
                    'status' => $formModel->status,
                    'sort_order' => $formModel->sort_order
                ];
            }
        } else {
            $this->editingForm = null;
            $this->resetForm();
        }
        
        $this->showModal = true;
        $this->clearMessages();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingForm = null;
        $this->resetForm();
        $this->file = null;
        $this->resetErrorBag();
    }

    public function resetForm()
    {
        $this->form = [
            'title' => '',
            'description' => '',
            'category' => 'application',
            'status' => true,
            'sort_order' => 0
        ];
    }

    public function save()
    {
        $this->clearMessages();
        $this->startProcessing();

        $rules = [
            'form.title' => 'required|string|max:255',
            'form.description' => 'nullable|string|max:1000',
            'form.category' => 'required|string|in:application,scholarship,research,administrative,academic,other',
            'form.sort_order' => 'required|integer|min:0',
        ];

        if (!$this->editingForm) {
            $rules['file'] = 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240';
        } else {
            $rules['file'] = 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240';
        }

        $this->validate($rules);

        try {
            if ($this->editingForm) {
                $this->formService->updateForm(
                    $this->editingForm['id'],
                    $this->form,
                    $this->file
                );
                $message = 'Form updated successfully!';
            } else {
                $this->formService->createForm(
                    $this->form,
                    $this->file,
                    Auth::id()
                );
                $message = 'Form created successfully!';
            }

            $this->closeModal();
            $this->setSuccessMessage($message);

        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (Exception $e) {
            $this->handleException($e, 'save form');
        } finally {
            $this->stopProcessing();
        }
    }

    public function toggleStatus($formId)
    {
        $this->clearMessages();
        $this->startProcessing();

        try {
            $form = $this->formService->toggleFormStatus($formId);
            $status = $form->status ? 'activated' : 'deactivated';
            $this->setSuccessMessage("Form {$status} successfully!");

        } catch (Exception $e) {
            $this->handleException($e, 'update form status');
        } finally {
            $this->stopProcessing();
        }
    }

    public function delete($formId)
    {
        $this->clearMessages();
        $this->startProcessing();

        try {
            $this->formService->deleteForm($formId);
            $this->setSuccessMessage('Form deleted successfully!');

        } catch (Exception $e) {
            $this->handleException($e, 'delete form');
        } finally {
            $this->stopProcessing();
        }
    }

    public function reorder($orderData)
    {
        $this->clearMessages();
        $this->startProcessing();

        try {
            $this->formService->reorderForms($orderData);
            $this->setSuccessMessage('Forms reordered successfully!');

        } catch (Exception $e) {
            $this->handleException($e, 'reorder forms');
        } finally {
            $this->stopProcessing();
        }
    }

    public function getCategories()
    {
        return $this->formService->getCategories();
    }

    public function getFileUrl($form)
    {
        if (!isset($form['file_path']) || !$form['file_path']) {
            return null;
        }

        return file_exists(public_path($form['file_path'])) ? asset($form['file_path']) : null;
    }

    public function getFormattedFileSize($form)
    {
        if (!isset($form['file_size']) || !$form['file_size']) {
            return 'Unknown';
        }

        $bytes = $form['file_size'];
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function render()
    {
        $forms = $this->formService->getFormsPaginated(
            $this->perPage,
            $this->search,
            $this->categoryFilter
        );

        // Apply status filter if needed
        if ($this->statusFilter !== '') {
            $forms = $this->formService->searchForms(
                $this->search,
                $this->categoryFilter,
                $this->statusFilter === '1'
            );
            
            // Manual pagination for filtered results
            $currentPage = request()->get('page', 1);
            $perPage = $this->perPage;
            $total = $forms->count();
            $items = $forms->slice(($currentPage - 1) * $perPage, $perPage)->values();
            
            $forms = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'pageName' => 'page']
            );
        }

        return view('livewire.admin.downloadable-form-management', [
            'forms' => $forms,
            'categories' => $this->getCategories()
        ]);
    }
}