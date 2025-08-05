<?php

namespace App\Services;

use App\Services\AuditService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class BaseWebsiteService
{
    protected $auditService;
    protected $model;
    protected $entityName;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    abstract protected function getModel(): string;
    abstract protected function getEntityName(): string;

    public function create(array $data): Model
    {
        try {
            $model = $this->getModel();
            $entity = $model::create($data);

            $this->auditService->log(
                strtolower($this->getEntityName()) . '_created',
                $this->getEntityName(),
                $entity->getKey(),
                'Created ' . strtolower($this->getEntityName()) . ': ' . ($entity->title ?? $entity->name ?? $entity->id)
            );

            return $entity;
        } catch (Exception $e) {
            Log::error('Failed to create ' . strtolower($this->getEntityName()), [
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function update(int $id, array $data): Model
    {
        try {
            $model = $this->getModel();
            $entity = $model::findOrFail($id);
            $entity->update($data);

            $this->auditService->log(
                strtolower($this->getEntityName()) . '_updated',
                $this->getEntityName(),
                $entity->getKey(),
                'Updated ' . strtolower($this->getEntityName()) . ': ' . ($entity->title ?? $entity->name ?? $entity->id)
            );

            return $entity;
        } catch (Exception $e) {
            Log::error('Failed to update ' . strtolower($this->getEntityName()), [
                'id' => $id,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $model = $this->getModel();
            $entity = $model::findOrFail($id);
            $identifier = $entity->title ?? $entity->name ?? $entity->id;
            
            $entity->delete();

            $this->auditService->log(
                strtolower($this->getEntityName()) . '_deleted',
                $this->getEntityName(),
                $id,
                'Deleted ' . strtolower($this->getEntityName()) . ': ' . $identifier
            );

            return true;
        } catch (Exception $e) {
            Log::error('Failed to delete ' . strtolower($this->getEntityName()), [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function toggleStatus(int $id, string $statusField = 'is_active'): Model
    {
        try {
            $model = $this->getModel();
            $entity = $model::findOrFail($id);
            $entity->update([$statusField => !$entity->{$statusField}]);

            $status = $entity->{$statusField} ? 'activated' : 'deactivated';

            $this->auditService->log(
                strtolower($this->getEntityName()) . '_status_changed',
                $this->getEntityName(),
                $entity->getKey(),
                ucfirst($this->getEntityName()) . " {$status}: " . ($entity->title ?? $entity->name ?? $entity->id)
            );

            return $entity;
        } catch (Exception $e) {
            Log::error('Failed to toggle status for ' . strtolower($this->getEntityName()), [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function findById(int $id): ?Model
    {
        $model = $this->getModel();
        return $model::find($id);
    }

    public function getAll(array $with = [])
    {
        $model = $this->getModel();
        $query = $model::query();
        
        if (!empty($with)) {
            $query->with($with);
        }
        
        return $query->get();
    }
}