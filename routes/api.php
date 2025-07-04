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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return ApiResponse::success(new UserResource($request->user()));
});

// Example API routes for future implementation
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

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

// Health check route (no authentication required)
Route::get('/health', function () {
    return ApiResponse::success(['status' => 'ok'], 'API is operational');
});

// Example routes for demonstrating exception handling (no authentication required)
Route::prefix('example')->group(function () {
    Route::get('/bad-request', [\App\Http\Controllers\ExampleExceptionController::class, 'badRequest']);
    Route::get('/validation-error', [\App\Http\Controllers\ExampleExceptionController::class, 'validationError']);
    Route::get('/unauthorized', [\App\Http\Controllers\ExampleExceptionController::class, 'unauthorized']);
    Route::get('/forbidden', [\App\Http\Controllers\ExampleExceptionController::class, 'forbidden']);
    Route::get('/not-found', [\App\Http\Controllers\ExampleExceptionController::class, 'notFound']);
    Route::get('/server-error', [\App\Http\Controllers\ExampleExceptionController::class, 'serverError']);
    Route::get('/try-catch', [\App\Http\Controllers\ExampleExceptionController::class, 'tryCatchExample']);
});
