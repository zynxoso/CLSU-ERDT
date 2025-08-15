<?php

namespace App\Services;

use App\Models\ApplicationTimeline;
use App\Services\BaseWebsiteService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ApplicationTimelineService extends BaseWebsiteService
{
    protected function getModel(): string
    {
        return ApplicationTimeline::class;
    }

    protected function getEntityName(): string
    {
        return 'Application Timeline';
    }

    public function getAllOrdered(): Collection
    {
        return ApplicationTimeline::orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getActive(): Collection
    {
        return ApplicationTimeline::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createTimeline(array $data): ApplicationTimeline
    {
        return $this->create($data);
    }

    public function updateTimeline(int $id, array $data): ApplicationTimeline
    {
        return $this->update($id, $data);
    }

    public function toggleTimelineStatus(int $id): ApplicationTimeline
    {
        return $this->toggleStatus($id, 'is_active');
    }

    public function searchTimelines(
        string $search = '',
        string $statusFilter = '',
        string $sortBy = 'sort_order',
        string $sortDirection = 'asc'
    ): Collection {
        $query = ApplicationTimeline::query();

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('phase', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($statusFilter !== '') {
            $query->where('is_active', (bool) $statusFilter);
        }

        // Apply sorting
        $allowedSortFields = ['sort_order', 'activity', 'first_semester', 'second_semester', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('sort_order', 'asc');
        }

        return $query->get();
    }

    public function getTimelinesPaginated(
        int $perPage = 15,
        string $search = '',
        string $statusFilter = '',
        string $sortBy = 'sort_order',
        string $sortDirection = 'asc'
    ): LengthAwarePaginator {
        $query = ApplicationTimeline::query();

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('phase', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($statusFilter !== '') {
            $query->where('is_active', (bool) $statusFilter);
        }

        // Apply sorting
        $allowedSortFields = ['sort_order', 'activity', 'first_semester', 'second_semester', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('sort_order', 'asc');
        }

        return $query->paginate($perPage);
    }

    public function getCurrentPhase(): ?ApplicationTimeline
    {
        $now = now();
        
        // Since the database schema doesn't have start_date/end_date columns,
        // we'll return the first active timeline item ordered by sort_order
        return ApplicationTimeline::where('is_active', true)
            ->orderBy('sort_order')
            ->first();
    }

    public function getUpcomingPhases(int $limit = 5): Collection
    {
        // Since the database schema doesn't have start_date column,
        // we'll return active timeline items ordered by sort_order
        return ApplicationTimeline::where('is_active', true)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }

    public function getPhasesByDateRange(\DateTime $startDate, \DateTime $endDate): Collection
    {
        // Since the database schema doesn't have start_date/end_date columns,
        // we'll return all active timeline items ordered by sort_order
        return ApplicationTimeline::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function reorderTimelines(array $orderData): bool
    {
        try {
            foreach ($orderData as $item) {
                ApplicationTimeline::where('id', $item['id'])
                    ->update(['sort_order' => $item['sort_order']]);
            }

            $this->auditService->log(
                'application_timeline_reordered',
                'Application Timeline',
                null,
                'Application timeline items reordered'
            );

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getTimelineStatistics(): array
    {
        $total = ApplicationTimeline::count();
        $active = ApplicationTimeline::where('is_active', true)->count();
        $current = $this->getCurrentPhase();
        $upcoming = $this->getUpcomingPhases()->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $total - $active,
            'current_phase' => $current ? $current->title : 'No active phase',
            'upcoming_count' => $upcoming
        ];
    }

    public function validateTimelineDates(array $data): bool
    {
        // Since the database schema doesn't have start_date/end_date columns,
        // we'll just validate that required fields are present
        if (empty($data['activity'])) {
            throw new \InvalidArgumentException('Activity is required');
        }
        
        if (empty($data['first_semester'])) {
            throw new \InvalidArgumentException('First semester is required');
        }
        
        if (empty($data['second_semester'])) {
            throw new \InvalidArgumentException('Second semester is required');
        }

        return true;
    }

    public function getActiveTimelines(): Collection
    {
        return $this->getActive();
    }

    public function getActiveCount(): int
    {
        return ApplicationTimeline::where('is_active', true)->count();
    }

    public function getTotalCount(): int
    {
        return ApplicationTimeline::count();
    }

    public function search(string $searchTerm = '', string $filterStatus = '', int $perPage = 10)
    {
        $query = ApplicationTimeline::query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('activity', 'like', "%{$searchTerm}%")
                  ->orWhere('first_semester', 'like', "%{$searchTerm}%")
                  ->orWhere('second_semester', 'like', "%{$searchTerm}%");
            });
        }

        if ($filterStatus !== '') {
            $isActive = $filterStatus === 'active' || $filterStatus === '1' || $filterStatus === 1;
            $query->where('is_active', $isActive);
        }

        return $query->orderBy('sort_order')->orderBy('created_at')->paginate($perPage);
    }
}