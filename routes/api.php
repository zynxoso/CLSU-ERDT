<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ApiResponse;

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

// Analytics API endpoints (strict rate limiting for sensitive data)
Route::prefix('admin')->middleware(['auth:sanctum', 'api.rate.limit:admin'])->group(function () {
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'apiData'])->name('api.admin.analytics');
});

// Scholar API endpoints (default rate limiting)
Route::prefix('scholar')->middleware(['auth:sanctum', 'web', 'api.rate.limit:default'])->group(function () {
    Route::get('/analytics', function (Request $request) {
        // Future implementation for scholar analytics
        return ApiResponse::success(['message' => 'Scholar analytics API - Coming soon']);
    })->name('api.scholar.analytics');

    // Status updates endpoint - actually used in scholar dashboard
    Route::get('/status-updates', [\App\Http\Controllers\Scholar\StatusUpdateController::class, 'index']);
});
