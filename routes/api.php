<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\UserResource;
use App\Http\Resources\ScholarProfileResource;
use App\Http\Resources\FundRequestResource;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\ManuscriptResource;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Models\ScholarProfile;
use App\Models\FundRequest;
use App\Models\Document;
use App\Models\Manuscript;
use App\Models\Notification;

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

// Example API route that returns the authenticated user
Route::middleware(['auth:sanctum', 'api.rate.limit:default'])->get('/user', function (Request $request) {
    return ApiResponse::success(new UserResource($request->user()));
});

// Example API routes for future implementation
Route::prefix('v1')->middleware(['auth:sanctum', 'api.rate.limit:default'])->group(function () {

    // User routes
    Route::get('/users/{id}', function ($id) {
        $user = User::findOrFail($id);
        return ApiResponse::success(new UserResource($user));
    });

    // Scholar profile routes
    Route::get('/scholar-profiles/{id}', function ($id) {
        $profile = ScholarProfile::with('user')->findOrFail($id);
        return ApiResponse::success(new ScholarProfileResource($profile));
    });

    // Fund request routes
    Route::get('/fund-requests/{id}', function ($id) {
        $fundRequest = FundRequest::with(['scholarProfile', 'requestType'])->findOrFail($id);
        return ApiResponse::success(new FundRequestResource($fundRequest));
    });

    // Document routes
    Route::get('/documents/{id}', function ($id) {
        $document = Document::findOrFail($id);
        return ApiResponse::success(new DocumentResource($document));
    });

    // Manuscript routes
    Route::get('/manuscripts/{id}', function ($id) {
        $manuscript = Manuscript::with(['scholarProfile'])->findOrFail($id);
        return ApiResponse::success(new ManuscriptResource($manuscript));
    });

    // Notification routes
    Route::get('/notifications/{id}', function ($id) {
        $notification = Notification::findOrFail($id);
        return ApiResponse::success(new NotificationResource($notification));
    });
});

// Health check route (public endpoint with lenient rate limiting)
Route::get('/health', function () {
    return ApiResponse::success(['status' => 'ok'], 'API is operational');
})->middleware('api.rate.limit:public');

// Example routes for demonstrating exception handling (public endpoints with moderate rate limiting)
Route::prefix('example')->middleware('api.rate.limit:public')->group(function () {
    Route::get('/bad-request', [\App\Http\Controllers\ExampleExceptionController::class, 'badRequest']);
    Route::get('/validation-error', [\App\Http\Controllers\ExampleExceptionController::class, 'validationError']);
    Route::get('/unauthorized', [\App\Http\Controllers\ExampleExceptionController::class, 'unauthorized']);
    Route::get('/forbidden', [\App\Http\Controllers\ExampleExceptionController::class, 'forbidden']);
    Route::get('/not-found', [\App\Http\Controllers\ExampleExceptionController::class, 'notFound']);
    Route::get('/server-error', [\App\Http\Controllers\ExampleExceptionController::class, 'serverError']);
    Route::get('/try-catch', [\App\Http\Controllers\ExampleExceptionController::class, 'tryCatchExample']);
});

// Analytics API endpoints (strict rate limiting for sensitive data)
Route::prefix('admin')->middleware(['auth:sanctum', 'api.rate.limit:admin'])->group(function () {
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'apiData'])->name('api.admin.analytics');
});

// Scholar API endpoints (default rate limiting)
Route::prefix('scholar')->middleware(['auth:sanctum', 'api.rate.limit:default'])->group(function () {
    Route::get('/analytics', function (Request $request) {
        // Future implementation for scholar analytics
        return ApiResponse::success(['message' => 'Scholar analytics API - Coming soon']);
    })->name('api.scholar.analytics');
});
