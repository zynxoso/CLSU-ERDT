<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Traits\HasErrorHandling;
use App\Services\LivewireErrorHandlingService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ErrorHandlingDemo extends Component
{
    use HasErrorHandling, AuthorizesRequests;

    public string $testInput = '';
    public string $demoType = 'validation';
    
    protected LivewireErrorHandlingService $errorService;

    public function boot(LivewireErrorHandlingService $errorService): void
    {
        $this->errorService = $errorService;
    }

    protected array $rules = [
        'testInput' => 'required|min:5|max:50',
    ];

    protected array $messages = [
        'testInput.required' => 'Test input is required.',
        'testInput.min' => 'Test input must be at least 5 characters.',
        'testInput.max' => 'Test input cannot exceed 50 characters.',
    ];

    public function testValidationError(): void
    {
        $this->executeWithErrorHandling(function () {
            $this->validate();
            $this->setSuccessMessage('Validation passed successfully!');
        });
    }

    public function testDatabaseError(): void
    {
        $this->executeWithErrorHandling(function () {
            // Simulate a database error by trying to insert into a non-existent table
            DB::table('non_existent_table')->insert(['data' => 'test']);
        });
    }

    public function testAuthorizationError(): void
    {
        $this->executeWithErrorHandling(function () {
            // Simulate authorization error
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                throw new \Illuminate\Auth\Access\AuthorizationException('You are not authorized to perform this action.');
            }
            $this->setSuccessMessage('Authorization check passed!');
        });
    }

    public function testGeneralError(): void
    {
        $this->executeWithErrorHandling(function () {
            // Simulate a general error
            throw new \Exception('This is a simulated general error for testing purposes.');
        });
    }

    public function testNetworkError(): void
    {
        $this->executeWithErrorHandling(function () {
            // Simulate a network/API error
            $response = file_get_contents('https://nonexistent-api-endpoint.invalid/data');
            if ($response === false) {
                throw new \Exception('Failed to fetch data from external API.');
            }
        });
    }

    public function testQueryException(): void
    {
        $this->executeWithErrorHandling(function () {
            // Simulate a query exception
            throw new QueryException(
                'mysql',
                'SELECT * FROM invalid_table WHERE invalid_column = ?',
                ['test'],
                new \PDOException('Table \'invalid_table\' doesn\'t exist')
            );
        });
    }

    public function clearAllErrors(): void
    {
        $this->clearErrors();
        $this->testInput = '';
        $this->demoType = 'validation';
    }

    public function retryLastOperation(): void
    {
        $this->retryFailedOperation();
    }

    public function render()
    {
        return view('livewire.error-handling-demo');
    }
}