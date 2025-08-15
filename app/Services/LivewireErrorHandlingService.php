<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Throwable;

/**
 * Service para sa Livewire component error handling.
 * 
 * Ang service na ito ay tumutulong sa pag-handle ng mga error
 * na nangyayari sa loob ng Livewire components.
 */
class LivewireErrorHandlingService
{
    /**
     * Handle Livewire component errors gracefully.
     *
     * @param Component $component
     * @param Throwable $e
     * @param string $action
     * @return void
     */
    public function handleComponentError(Component $component, Throwable $e, string $action = 'unknown'): void
    {
        // Log the error with component context
        $this->logComponentError($component, $e, $action);
        
        // Set error state in component
        $this->setComponentErrorState($component, $e, $action);
        
        // Emit error event for frontend handling
        $this->emitErrorEvent($component, $e, $action);
    }
    
    /**
     * Handle validation errors in Livewire components.
     *
     * @param Component $component
     * @param array $errors
     * @param string $action
     * @return void
     */
    public function handleValidationErrors(Component $component, array $errors, string $action = 'validation'): void
    {
        // Log validation errors
        Log::info('Livewire validation errors', [
            'component' => get_class($component),
            'action' => $action,
            'errors' => $errors,
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString()
        ]);
        
        // Set validation error state
        if (property_exists($component, 'validationErrors')) {
            $component->validationErrors = $errors;
        }
        
        // Set error message
        if (property_exists($component, 'errorMessage')) {
            $component->errorMessage = 'Please correct the validation errors and try again.';
        }
        
        // Emit validation error event
        $component->dispatch('validation-error', [
            'errors' => $errors,
            'action' => $action
        ]);
    }
    
    /**
     * Handle database errors in Livewire components.
     *
     * @param Component $component
     * @param Throwable $e
     * @param string $action
     * @return void
     */
    public function handleDatabaseError(Component $component, Throwable $e, string $action = 'database'): void
    {
        // Log database error
        Log::error('Livewire database error', [
            'component' => get_class($component),
            'action' => $action,
            'error' => $e->getMessage(),
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString()
        ]);
        
        // Set user-friendly error message
        $message = $this->getDatabaseErrorMessage($e);
        
        if (property_exists($component, 'errorMessage')) {
            $component->errorMessage = $message;
        }
        
        // Emit database error event
        $component->dispatch('database-error', [
            'message' => $message,
            'action' => $action
        ]);
    }
    
    /**
     * Handle authorization errors in Livewire components.
     *
     * @param Component $component
     * @param string $action
     * @param string $resource
     * @return void
     */
    public function handleAuthorizationError(Component $component, string $action, string $resource = 'resource'): void
    {
        // Log authorization error
        Log::warning('Livewire authorization error', [
            'component' => get_class($component),
            'action' => $action,
            'resource' => $resource,
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString()
        ]);
        
        $message = "You don't have permission to {$action} this {$resource}.";
        
        if (property_exists($component, 'errorMessage')) {
            $component->errorMessage = $message;
        }
        
        // Emit authorization error event
        $component->dispatch('authorization-error', [
            'message' => $message,
            'action' => $action,
            'resource' => $resource
        ]);
    }
    
    /**
     * Clear error state in component.
     *
     * @param Component $component
     * @return void
     */
    public function clearErrorState(Component $component): void
    {
        if (property_exists($component, 'errorMessage')) {
            $component->errorMessage = null;
        }
        
        if (property_exists($component, 'validationErrors')) {
            $component->validationErrors = [];
        }
        
        if (property_exists($component, 'hasError')) {
            $component->hasError = false;
        }
        
        // Emit clear error event
        $component->dispatch('error-cleared');
    }
    
    /**
     * Set success state in component.
     *
     * @param Component $component
     * @param string $message
     * @param string $action
     * @return void
     */
    public function setSuccessState(Component $component, string $message, string $action = 'success'): void
    {
        // Clear any existing errors
        $this->clearErrorState($component);
        
        if (property_exists($component, 'successMessage')) {
            $component->successMessage = $message;
        }
        
        // Emit success event
        $component->dispatch('operation-success', [
            'message' => $message,
            'action' => $action
        ]);
    }
    
    /**
     * Log component error with detailed context.
     */
    private function logComponentError(Component $component, Throwable $e, string $action): void
    {
        Log::error('Livewire component error', [
            'component' => get_class($component),
            'action' => $action,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'user_id' => Auth::id(),
            'component_properties' => $this->getComponentProperties($component),
            'stack_trace' => $e->getTraceAsString(),
            'timestamp' => now()->toISOString()
        ]);
    }
    
    /**
     * Set error state in component.
     */
    private function setComponentErrorState(Component $component, Throwable $e, string $action): void
    {
        $message = $this->getErrorMessage($e, $action);
        
        if (property_exists($component, 'errorMessage')) {
            $component->errorMessage = $message;
        }
        
        if (property_exists($component, 'hasError')) {
            $component->hasError = true;
        }
        
        // Clear any success messages
        if (property_exists($component, 'successMessage')) {
            $component->successMessage = null;
        }
    }
    
    /**
     * Emit error event for frontend handling.
     */
    private function emitErrorEvent(Component $component, Throwable $e, string $action): void
    {
        $component->dispatch('component-error', [
            'message' => $this->getErrorMessage($e, $action),
            'action' => $action,
            'component' => get_class($component)
        ]);
    }
    
    /**
     * Get user-friendly error message.
     */
    private function getErrorMessage(Throwable $e, string $action): string
    {
        // In production, hide detailed error messages
        if (app()->environment('production') && !($e instanceof \App\Exceptions\BaseException)) {
            return "An error occurred while trying to {$action}. Please try again.";
        }
        
        return $e->getMessage() ?: "An unexpected error occurred during {$action}.";
    }
    
    /**
     * Get database-specific error message.
     */
    private function getDatabaseErrorMessage(Throwable $e): string
    {
        if ($e instanceof \Illuminate\Database\QueryException) {
            $errorCode = $e->errorInfo[1] ?? null;
            
            switch ($errorCode) {
                case 1062:
                    return 'This record already exists. Please check your data.';
                case 1452:
                    return 'Invalid reference data. Please check your selection.';
                case 1451:
                    return 'Cannot delete this record because it is being used by other data.';
                case 2006:
                    return 'Database connection lost. Please try again.';
                default:
                    return 'A database error occurred. Please try again.';
            }
        }
        
        return 'A database error occurred. Please try again.';
    }
    
    /**
     * Get component properties for logging (excluding sensitive data).
     */
    private function getComponentProperties(Component $component): array
    {
        $properties = [];
        $reflection = new \ReflectionClass($component);
        
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();
            
            // Skip sensitive properties
            if (in_array($name, ['password', 'token', 'secret', 'key'])) {
                continue;
            }
            
            try {
                $value = $property->getValue($component);
                $properties[$name] = is_object($value) ? get_class($value) : $value;
            } catch (Throwable $e) {
                $properties[$name] = 'Unable to retrieve';
            }
        }
        
        return $properties;
    }
}