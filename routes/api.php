<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Api\ErrorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Basic health check route (public endpoint with lenient rate limiting)
Route::get('/health', function () {
    return ApiResponse::success(['status' => 'ok'], 'API is operational');
})->middleware('api.rate.limit:public');

// Error handling routes
Route::prefix('errors')->group(function () {
    // Client-side error logging
    Route::post('/log-client-error', [ErrorController::class, 'logClientError'])
        ->middleware(['throttle:60,1']); // Allow 60 error logs per minute
    
    // Health check endpoint
    Route::get('/health', [ErrorController::class, 'healthCheck']);
    
    // Error statistics (protected)
    Route::get('/stats', [ErrorController::class, 'getErrorStats'])
        ->middleware(['auth:sanctum', 'throttle:10,1']);
    
    // Test error endpoints (development only)
    Route::post('/test', [ErrorController::class, 'testError'])
        ->middleware(['throttle:5,1']);
});

// Specific error handling endpoints
Route::prefix('handle')->group(function () {
    Route::get('/validation', [ErrorController::class, 'handleValidationError']);
    Route::get('/auth', [ErrorController::class, 'handleAuthError']);
    Route::get('/authorization', [ErrorController::class, 'handleAuthorizationError']);
    Route::get('/not-found', [ErrorController::class, 'handleNotFoundError']);
    Route::get('/method-not-allowed', [ErrorController::class, 'handleMethodNotAllowed']);
    Route::get('/rate-limit', [ErrorController::class, 'handleRateLimitError']);
    Route::get('/server-error', [ErrorController::class, 'handleServerError']);
    Route::get('/service-unavailable', [ErrorController::class, 'handleServiceUnavailable']);
    Route::get('/database-error', [ErrorController::class, 'handleDatabaseError']);
    Route::get('/csrf-error', [ErrorController::class, 'handleCSRFError']);
    Route::get('/maintenance', [ErrorController::class, 'handleMaintenanceMode']);
});

// Scholar API endpoints (default rate limiting)
Route::prefix('scholar')->middleware(['auth:web', 'api.rate.limit:default'])->group(function () {
    // Future API endpoints can be added here
});
