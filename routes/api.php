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

// Scholar API endpoints (default rate limiting)
Route::prefix('scholar')->middleware(['auth:web', 'api.rate.limit:default'])->group(function () {
    // Future API endpoints can be added here
});
