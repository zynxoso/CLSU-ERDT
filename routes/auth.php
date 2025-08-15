<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ScholarAuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// Admin/SuperAdmin Login routes
Route::middleware('guest:web')->group(function () {
    Route::get('CLSU-ERDT-COORDINATOR', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('CLSU-ERDT-COORDINATOR', [AuthenticatedSessionController::class, 'store']);
});

// Scholar Login routes
Route::middleware('guest:scholar')->group(function () {
    Route::get('CLSU-ERDT-SCHOLARSHIP', [ScholarAuthenticatedSessionController::class, 'create'])
        ->name('scholar.login');
    Route::post('CLSU-ERDT-SCHOLARSHIP', [ScholarAuthenticatedSessionController::class, 'store']);
});

// Admin logout
Route::middleware('auth:web')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// Scholar logout
Route::middleware('auth:scholar')->group(function () {
    Route::post('scholar/logout', [ScholarAuthenticatedSessionController::class, 'destroy'])
        ->name('scholar-logout');
});
