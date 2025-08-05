<?php

namespace App\Services;

use App\Models\FacultyMember;
use App\Services\BaseWebsiteService;
use App\Services\FileManagementService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class FacultyService extends BaseWebsiteService
{
    private $fileService;

    public function __construct(AuditService $auditService, FileManagementService $fileService)
    {
        parent::__construct($auditService);
        $this->fileService = $fileService;
    }

    protected function getModel(): string
    {
        return FacultyMember::class;
    }

    protected function getEntityName(): string
    {
        return 'Faculty Member';
    }

    public function getAllOrdered(): Collection
    {
        return FacultyMember::ordered()->get();
    }

    public function getByDepartment(string $department): Collection
    {
        return FacultyMember::where('department', $department)
            ->ordered()
            ->get();
    }

    public function createFaculty(array $data, ?UploadedFile $photo = null): FacultyMember
    {
        if ($photo) {
            $photoData = $this->fileService->uploadFile(
                $photo,
                'experts',
                ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                2048
            );
            $data['photo_path'] = basename($photoData['file_path']);
        }

        return $this->create($data);
    }

    public function updateFaculty(int $id, array $data, ?UploadedFile $photo = null): FacultyMember
    {
        $faculty = FacultyMember::findOrFail($id);

        if ($photo) {
            // Delete old photo if exists
            if ($faculty->photo_path) {
                $this->fileService->deleteFile('experts/' . $faculty->photo_path);
            }

            // Upload new photo
            $photoData = $this->fileService->uploadFile(
                $photo,
                'experts',
                ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                2048
            );
            $data['photo_path'] = basename($photoData['file_path']);
        }

        return $this->update($id, $data);
    }

    public function deleteFaculty(int $id): bool
    {
        $faculty = FacultyMember::findOrFail($id);
        
        // Delete photo if exists
        if ($faculty->photo_path) {
            $this->fileService->deleteFile('experts/' . $faculty->photo_path);
        }

        return $this->delete($id);
    }

    public function searchFaculty(string $search = '', string $department = ''): Collection
    {
        $query = FacultyMember::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        if ($department) {
            $query->where('department', $department);
        }

        return $query->ordered()->get();
    }

    public function getDepartments(): array
    {
        return FacultyMember::distinct('department')
            ->pluck('department')
            ->filter()
            ->sort()
            ->values()
            ->toArray();
    }

    public function getDepartmentsList(): array
    {
        return $this->getDepartments();
    }

    public function reorderFaculty(array $orderData): bool
    {
        try {
            foreach ($orderData as $item) {
                FacultyMember::where('id', $item['id'])
                    ->update(['sort_order' => $item['sort_order']]);
            }

            $this->auditService->log(
                'faculty_reordered',
                'Faculty Member',
                null,
                'Faculty members reordered'
            );

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getPhotoUrl(FacultyMember $faculty): ?string
    {
        if (!$faculty->photo_path) {
            return null;
        }

        $photoPath = 'experts/' . $faculty->photo_path;
        return $this->fileService->fileExists($photoPath) 
            ? asset($photoPath) 
            : null;
    }

    public function getRecentFaculty(int $limit = 5): Collection
    {
        return FacultyMember::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getActiveCount(): int
    {
        return FacultyMember::where('is_active', true)->count();
    }

    public function getTotalCount(): int
    {
        return FacultyMember::count();
    }

    public function search(string $searchTerm = '', string $filterStatus = '', int $perPage = 10)
    {
        $query = FacultyMember::query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('position', 'like', "%{$searchTerm}%")
                  ->orWhere('department', 'like', "%{$searchTerm}%")
                  ->orWhere('specialization', 'like', "%{$searchTerm}%");
            });
        }

        if ($filterStatus !== '') {
            $isActive = $filterStatus === 'active' || $filterStatus === '1' || $filterStatus === 1;
            $query->where('is_active', $isActive);
        }

        return $query->orderBy('sort_order')->orderBy('name')->paginate($perPage);
    }
}