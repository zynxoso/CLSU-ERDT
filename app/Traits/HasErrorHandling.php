<?php

namespace App\Traits;

use App\Services\LivewireErrorHandlingService;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

/**
 * Trait para sa error handling sa Livewire components.
 * 
 * Ang trait na ito ay nagbibigay ng consistent error handling
 * across all Livewire components.
 */
trait HasErrorHandling
{
    /**
     * Error message na ipapakita sa user.
     */
    public ?string $errorMessage = null;
    
    /**
     * Success message na ipapakita sa user.
     */
    public ?string $successMessage = null;
    
    /**
     * Validation errors array.
     */
    public array $validationErrors = [];
    
    /**
     * Flag kung may error ba.
     */
    public bool $hasError = false;
    
    /**
     * Flag kung nag-loading ba.
     */
    public bool $isLoading = false;
    
    /**
     * Error handling service instance.
     */
    protected ?LivewireErrorHandlingService $errorService = null;
    
    /**
     * Initialize error handling.
     */
    public function initializeHasErrorHandling(): void
    {
        $this->errorService = app(LivewireErrorHandlingService::class);
    }
    
    /**
     * Execute action with error handling.
     *
     * @param callable $action
     * @param string $actionName
     * @param bool $showLoading
     * @return mixed
     */
    protected function executeWithErrorHandling(callable $action, string $actionName = 'action', bool $showLoading = true)
    {
        try {
            // Clear previous errors
            $this->clearErrors();
            
            // Show loading state
            if ($showLoading) {
                $this->setLoading(true);
            }
            
            // Execute the action
            $result = $action();
            
            // Hide loading state
            if ($showLoading) {
                $this->setLoading(false);
            }
            
            return $result;
            
        } catch (ValidationException $e) {
            $this->handleValidationException($e, $actionName);
            return null;
            
        } catch (AuthorizationException $e) {
            $this->handleAuthorizationException($e, $actionName);
            return null;
            
        } catch (QueryException $e) {
            $this->handleDatabaseException($e, $actionName);
            return null;
            
        } catch (Throwable $e) {
            $this->handleGeneralException($e, $actionName);
            return null;
            
        } finally {
            // Always hide loading state
            if ($showLoading) {
                $this->setLoading(false);
            }
        }
    }
    
    /**
     * Handle validation exceptions.
     */
    protected function handleValidationException(ValidationException $e, string $action): void
    {
        $this->errorService?->handleValidationErrors($this, $e->errors(), $action);
        
        // Set validation errors for display
        $this->validationErrors = $e->errors();
        $this->hasError = true;
        
        // Flash validation errors to session for form handling
        session()->flash('errors', $e->validator->errors());
    }
    
    /**
     * Handle authorization exceptions.
     */
    protected function handleAuthorizationException(AuthorizationException $e, string $action): void
    {
        $resource = $this->getResourceName();
        $this->errorService?->handleAuthorizationError($this, $action, $resource);
        
        $this->hasError = true;
    }
    
    /**
     * Handle database exceptions.
     */
    protected function handleDatabaseException(QueryException $e, string $action): void
    {
        $this->errorService?->handleDatabaseError($this, $e, $action);
        $this->hasError = true;
    }
    
    /**
     * Handle general exceptions.
     */
    protected function handleGeneralException(Throwable $e, string $action): void
    {
        $this->errorService?->handleComponentError($this, $e, $action);
        $this->hasError = true;
    }
    
    /**
     * Clear all error states.
     */
    public function clearErrors(): void
    {
        $this->errorService?->clearErrorState($this);
    }
    
    /**
     * Set success message.
     */
    public function setSuccess(string $message, string $action = 'success'): void
    {
        $this->errorService?->setSuccessState($this, $message, $action);
    }
    
    /**
     * Set loading state.
     */
    public function setLoading(bool $loading): void
    {
        $this->isLoading = $loading;
        
        if ($loading) {
            $this->dispatch('loading-started');
        } else {
            $this->dispatch('loading-finished');
        }
    }
    
    /**
     * Validate data with error handling.
     */
    protected function validateWithErrorHandling(array $data, array $rules, string $action = 'validation'): array
    {
        try {
            return $this->validate($rules);
        } catch (ValidationException $e) {
            $this->handleValidationException($e, $action);
            throw $e;
        }
    }
    
    /**
     * Authorize action with error handling.
     */
    protected function authorizeWithErrorHandling(string $ability, $arguments = [], string $action = 'authorization'): void
    {
        try {
            $this->authorize($ability, $arguments);
        } catch (AuthorizationException $e) {
            $this->handleAuthorizationException($e, $action);
            throw $e;
        }
    }
    
    /**
     * Execute database operation with error handling.
     */
    protected function executeDbOperation(callable $operation, string $action = 'database operation')
    {
        try {
            return $operation();
        } catch (QueryException $e) {
            $this->handleDatabaseException($e, $action);
            throw $e;
        }
    }
    
    /**
     * Show error message.
     */
    public function showError(string $message, string $action = 'error'): void
    {
        $this->errorMessage = $message;
        $this->hasError = true;
        
        $this->dispatch('component-error', [
            'message' => $message,
            'action' => $action,
            'component' => static::class
        ]);
    }
    
    /**
     * Show success message.
     */
    public function showSuccess(string $message, string $action = 'success'): void
    {
        $this->successMessage = $message;
        $this->hasError = false;
        $this->errorMessage = null;
        
        $this->dispatch('operation-success', [
            'message' => $message,
            'action' => $action
        ]);
    }
    
    /**
     * Get resource name for authorization errors.
     */
    protected function getResourceName(): string
    {
        // Override this method in components to provide specific resource names
        return 'resource';
    }
    
    /**
     * Check if component has errors.
     */
    public function hasErrors(): bool
    {
        return $this->hasError || !empty($this->validationErrors) || !empty($this->errorMessage);
    }
    
    /**
     * Get all error messages.
     */
    public function getAllErrors(): array
    {
        $errors = [];
        
        if ($this->errorMessage) {
            $errors[] = $this->errorMessage;
        }
        
        foreach ($this->validationErrors as $field => $fieldErrors) {
            if (is_array($fieldErrors)) {
                $errors = array_merge($errors, $fieldErrors);
            } else {
                $errors[] = $fieldErrors;
            }
        }
        
        return $errors;
    }
    
    /**
     * Reset component state.
     */
    public function resetState(): void
    {
        $this->clearErrors();
        $this->successMessage = null;
        $this->isLoading = false;
        
        $this->dispatch('state-reset');
    }
    
    /**
     * Handle component hydration errors.
     */
    public function hydrate(): void
    {
        try {
            // Initialize error service if not already done
            if (!$this->errorService) {
                $this->initializeHasErrorHandling();
            }
        } catch (Throwable $e) {
            Log::error('Error during component hydration', [
                'component' => static::class,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * Handle component dehydration.
     */
    public function dehydrate(): void
    {
        // Clean up any temporary error states that shouldn't persist
        if ($this->isLoading) {
            $this->isLoading = false;
        }
    }
    
    /**
     * Get error state for frontend.
     */
    public function getErrorState(): array
    {
        return [
            'hasError' => $this->hasError,
            'errorMessage' => $this->errorMessage,
            'validationErrors' => $this->validationErrors,
            'isLoading' => $this->isLoading
        ];
    }
    
    /**
     * Retry failed operation.
     */
    public function retryOperation(callable $operation, string $action = 'retry', int $maxAttempts = 3): mixed
    {
        $attempts = 0;
        
        while ($attempts < $maxAttempts) {
            try {
                return $this->executeWithErrorHandling($operation, $action);
            } catch (Throwable $e) {
                $attempts++;
                
                if ($attempts >= $maxAttempts) {
                    $this->showError("Operation failed after {$maxAttempts} attempts. Please try again later.", $action);
                    throw $e;
                }
                
                // Wait before retry
                usleep(500000 * $attempts); // 0.5s, 1s, 1.5s delays
            }
        }
        
        return null;
    }
}