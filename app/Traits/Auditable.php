<?php

namespace App\Traits;

use App\Models\AuditLog;
use App\Services\AuditService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            self::logAction('create', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            self::logAction('update', $model, $model->getOriginal(), $model->getChanges());
        });

        static::deleted(function ($model) {
            self::logAction('delete', $model, $model->getAttributes(), null);
        });
    }

    /**
     * Log an action for the model.
     *
     * @param string $action
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return void
     */
    protected static function logAction($action, $model, $oldValues = null, $newValues = null)
    {
        try {
            $auditService = App::make(AuditService::class);
            
            switch ($action) {
                case 'create':
                    $auditService->logCreate(
                        class_basename($model),
                        $model->id,
                        $newValues
                    );
                    break;
                
                case 'update':
                    $auditService->logUpdate(
                        class_basename($model),
                        $model->id,
                        $oldValues,
                        $newValues
                    );
                    break;
                
                case 'delete':
                    $auditService->logDelete(
                        class_basename($model),
                        $model->id,
                        $oldValues
                    );
                    break;
                
                default:
                    $auditService->logCustomAction(
                        $action,
                        class_basename($model),
                        $model->id,
                        $newValues
                    );
                    break;
            }
        } catch (\Exception $e) {
            // Log the exception without disrupting the application
            \Illuminate\Support\Facades\Log::error('Failed to create audit log: ' . $e->getMessage());
        }
    }

    /**
     * Log a custom action for this model.
     *
     * @param string $action
     * @param array|null $context
     * @return void
     */
    public function logCustomAction($action, $context = null)
    {
        try {
            $auditService = App::make(AuditService::class);
            $auditService->logCustomAction(
                $action,
                class_basename($this),
                $this->id,
                $context
            );
        } catch (\Exception $e) {
            // Log the exception without disrupting the application
            \Illuminate\Support\Facades\Log::error('Failed to create custom audit log: ' . $e->getMessage());
        }
    }
} 