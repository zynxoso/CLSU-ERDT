<?php

namespace App\Traits;

trait WebsiteManagementTrait
{
    public $isProcessing = false;
    public $successMessage = '';
    public $errorMessage = '';

    protected function clearMessages()
    {
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    protected function setSuccessMessage($message)
    {
        $this->clearMessages();
        $this->successMessage = $message;
    }

    protected function setErrorMessage($message)
    {
        $this->clearMessages();
        $this->errorMessage = $message;
    }

    protected function startProcessing()
    {
        $this->isProcessing = true;
    }

    protected function stopProcessing()
    {
        $this->isProcessing = false;
    }

    protected function handleException(\Exception $e, $operation = 'operation')
    {
        $this->stopProcessing();
        $this->setErrorMessage("Failed to {$operation}: " . $e->getMessage());
        
        // Log the error for debugging
        \Illuminate\Support\Facades\Log::error("WebsiteManagement {$operation} failed", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}