<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log a create action.
     *
     * @param  string  $entityType
     * @param  int  $entityId
     * @param  array  $newValues
     * @return \App\Models\AuditLog
     */
    public function logCreate($entityType, $entityId, $newValues)
    {
        return $this->logAction($entityType, $entityId, 'create', null, $newValues);
    }

    /**
     * Log an update action.
     *
     * @param  string  $entityType
     * @param  int  $entityId
     * @param  array  $oldValues
     * @param  array  $newValues
     * @return \App\Models\AuditLog
     */
    public function logUpdate($entityType, $entityId, $oldValues, $newValues)
    {
        return $this->logAction($entityType, $entityId, 'update', $oldValues, $newValues);
    }

    /**
     * Log a delete action.
     *
     * @param  string  $entityType
     * @param  int  $entityId
     * @param  array  $oldValues
     * @return \App\Models\AuditLog
     */
    public function logDelete($entityType, $entityId, $oldValues)
    {
        return $this->logAction($entityType, $entityId, 'delete', $oldValues, null);
    }

    /**
     * Log an action.
     *
     * @param  string  $entityType
     * @param  int  $entityId
     * @param  string  $action
     * @param  array|null  $oldValues
     * @param  array|null  $newValues
     * @return \App\Models\AuditLog
     */
    public function logAction($entityType, $entityId, $action, $oldValues = null, $newValues = null)
    {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $entityType,
            'model_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Get audit logs for a specific entity.
     *
     * @param  string  $entityType
     * @param  int  $entityId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEntityLogs($entityType, $entityId)
    {
        return AuditLog::where('model_type', $entityType)
            ->where('model_id', $entityId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get recent audit logs.
     *
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentLogs($limit = 50)
    {
        return AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs by user.
     *
     * @param  int  $userId
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserLogs($userId, $limit = 50)
    {
        return AuditLog::where('user_id', $userId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Log a custom action performed on a model.
     *
     * @param string $action The action performed
     * @param string $modelType The type of model
     * @param int|null $modelId The ID of the model
     * @param array|null $context Additional context
     * @return \App\Models\AuditLog
     */
    public function logCustomAction(string $action, string $modelType, ?int $modelId = null, ?array $context = null): AuditLog
    {
        return $this->logAction($modelType, $modelId, $action, null, $context);
    }

    /**
     * Log an action (generic method).
     *
     * @param string $action The action performed
     * @param string $entityType The type of entity
     * @param int|null $entityId The ID of the entity
     * @param string|null $description Description of the action (stored in new_values)
     * @param array|null $data Additional data (merged with description)
     * @return \App\Models\AuditLog
     */
    public function log(string $action, string $entityType, ?int $entityId = null, ?string $description = null, ?array $data = null): AuditLog
    {
        // If description is provided, include it in the data array
        if ($description !== null) {
            $data = $data ?? [];
            $data['description'] = $description;
        }

        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $entityType,
            'model_id' => $entityId,
            'old_values' => null,
            'new_values' => $data,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
