<?php

namespace App\Services;

use App\Models\Announcement;
use App\Services\BaseWebsiteService;
use Illuminate\Database\Eloquent\Collection;

class AnnouncementService extends BaseWebsiteService
{
    protected function getModel(): string
    {
        return Announcement::class;
    }

    protected function getEntityName(): string
    {
        return 'Announcement';
    }

    public function getAllOrdered(): Collection
    {
        return Announcement::orderByPriority()->get();
    }

    public function getActive(): Collection
    {
        return Announcement::where('is_active', true)
            ->orderByPriority()
            ->get();
    }

    public function getActiveAnnouncements(): Collection
    {
        return $this->getActive();
    }

    public function getByType(string $type): Collection
    {
        return Announcement::where('type', $type)
            ->where('is_active', true)
            ->orderByPriority()
            ->get();
    }

    public function createAnnouncement(array $data): Announcement
    {
        $validatedData = $this->validateAnnouncementData($data);
        return $this->create($validatedData);
    }

    public function updateAnnouncement(int $id, array $data): Announcement
    {
        $validatedData = $this->validateAnnouncementData($data);
        return $this->update($id, $validatedData);
    }

    public function toggleAnnouncementStatus(int $id): Announcement
    {
        return $this->toggleStatus($id, 'is_active');
    }

    private function validateAnnouncementData(array $data): array
    {
        $allowedTypes = ['general', 'application', 'scholarship', 'event', 'urgent'];
        
        if (isset($data['type']) && !in_array($data['type'], $allowedTypes)) {
            throw new \InvalidArgumentException('Invalid announcement type');
        }

        if (isset($data['priority']) && ($data['priority'] < 0 || $data['priority'] > 10)) {
            throw new \InvalidArgumentException('Priority must be between 0 and 10');
        }

        return $data;
    }

    public function getAnnouncementTypes(): array
    {
        return [
            'general' => 'General',
            'application' => 'Application',
            'scholarship' => 'Scholarship',
            'event' => 'Event',
            'urgent' => 'Urgent'
        ];
    }

    public function searchAnnouncements(string $search = '', string $type = '', bool $activeOnly = false): Collection
    {
        $query = Announcement::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($activeOnly) {
            $query->where('is_active', true);
        }

        return $query->orderByPriority()->get();
    }

    public function getRecentAnnouncements(int $limit = 5): Collection
    {
        return Announcement::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getActiveCount(): int
    {
        return Announcement::where('is_active', true)->count();
    }

    public function getTotalCount(): int
    {
        return Announcement::count();
    }

    public function search(string $searchTerm = '', string $filterType = '', string $filterStatus = '', int $perPage = 10)
    {
        $query = Announcement::query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        if ($filterType) {
            $query->where('type', $filterType);
        }

        if ($filterStatus !== '') {
            $isActive = $filterStatus === 'active' || $filterStatus === '1' || $filterStatus === 1;
            $query->where('is_active', $isActive);
        }

        return $query->orderByPriority()->paginate($perPage);
    }
}