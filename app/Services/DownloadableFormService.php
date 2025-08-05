<?php

namespace App\Services;

use App\Models\DownloadableForm;
use App\Services\BaseWebsiteService;
use App\Services\FileManagementService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class DownloadableFormService extends BaseWebsiteService
{
    private $fileService;

    public function __construct(AuditService $auditService, FileManagementService $fileService)
    {
        parent::__construct($auditService);
        $this->fileService = $fileService;
    }

    protected function getModel(): string
    {
        return DownloadableForm::class;
    }

    protected function getEntityName(): string
    {
        return 'DownloadableForm';
    }

    public function getAllOrdered(): Collection
    {
        return DownloadableForm::with('uploader')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getActive(): Collection
    {
        return DownloadableForm::where('status', true)
            ->with('uploader')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByCategory(string $category): Collection
    {
        return DownloadableForm::where('category', $category)
            ->where('status', true)
            ->with('uploader')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createForm(array $data, UploadedFile $file, int $uploaderId): DownloadableForm
    {
        // Upload file
        $fileData = $this->fileService->uploadFile(
            $file,
            'forms',
            ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
            10240
        );

        // Merge file data with form data
        $formData = array_merge($data, $fileData, [
            'uploaded_by' => $uploaderId
        ]);

        return $this->create($formData);
    }

    public function updateForm(int $id, array $data, ?UploadedFile $file = null): DownloadableForm
    {
        $form = DownloadableForm::findOrFail($id);

        if ($file) {
            // Replace existing file
            $fileData = $this->fileService->replaceFile(
                $file,
                $form->file_path,
                'forms',
                ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
                10240
            );
            $data = array_merge($data, $fileData);
        }

        return $this->update($id, $data);
    }

    public function deleteForm(int $id): bool
    {
        $form = DownloadableForm::findOrFail($id);
        
        // Delete file if exists
        if ($form->file_path) {
            $this->fileService->deleteFile($form->file_path);
        }

        return $this->delete($id);
    }

    public function toggleFormStatus(int $id): DownloadableForm
    {
        return $this->toggleStatus($id, 'status');
    }

    public function searchForms(string $search = '', string $category = '', bool $activeOnly = false): Collection
    {
        $query = DownloadableForm::with('uploader');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('filename', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($activeOnly) {
            $query->where('status', true);
        }

        return $query->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getFormsPaginated(int $perPage = 15, string $search = '', string $category = ''): LengthAwarePaginator
    {
        $query = DownloadableForm::with('uploader');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('filename', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        return $query->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getCategories(): array
    {
        return [
            'application' => 'Application Forms',
            'scholarship' => 'Scholarship Forms',
            'research' => 'Research Forms',
            'administrative' => 'Administrative Forms',
            'academic' => 'Academic Forms',
            'other' => 'Other Forms'
        ];
    }

    public function reorderForms(array $orderData): bool
    {
        try {
            foreach ($orderData as $item) {
                DownloadableForm::where('id', $item['id'])
                    ->update(['sort_order' => $item['sort_order']]);
            }

            $this->auditService->log(
                'downloadable_forms_reordered',
                'DownloadableForm',
                null,
                'Downloadable forms reordered'
            );

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getFileUrl(DownloadableForm $form): ?string
    {
        if (!$form->file_path) {
            return null;
        }

        return $this->fileService->fileExists($form->file_path) 
            ? asset($form->file_path) 
            : null;
    }

    public function getFormattedFileSize(DownloadableForm $form): string
    {
        if (!$form->file_size) {
            return 'Unknown';
        }

        $bytes = $form->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getActiveForms(): Collection
    {
        return $this->getActive();
    }

    public function getFormCategories(): array
    {
        return $this->getCategories();
    }

    public function getRecentForms(int $limit = 5): Collection
    {
        return DownloadableForm::with('uploader')
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getActiveCount(): int
    {
        return DownloadableForm::where('status', true)->count();
    }

    public function getTotalCount(): int
    {
        return DownloadableForm::count();
    }

    public function search(string $searchTerm = '', string $filterCategory = '', string $filterStatus = '', int $perPage = 10)
    {
        $query = DownloadableForm::query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%");
            });
        }

        if ($filterCategory) {
            $query->where('category', $filterCategory);
        }

        if ($filterStatus !== '') {
            $isActive = $filterStatus === 'active' || $filterStatus === '1' || $filterStatus === 1;
            $query->where('status', $isActive);
        }

        return $query->orderBy('sort_order')->orderBy('title')->paginate($perPage);
    }
}