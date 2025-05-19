<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScholarController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FundRequestController;
use App\Http\Controllers\DisbursementController;
use App\Http\Controllers\ManuscriptController;

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScholarFundRequestController;
use App\Http\Controllers\ScholarProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DocumentController;

// Redirect to login if not authenticated, otherwise to appropriate dashboard
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'scholar') {
            return redirect()->route('scholar.dashboard');
        }
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Include authentication routes
require __DIR__.'/auth.php';

// Scholar routes - only accessible to scholars
Route::middleware(['auth'])->prefix('scholar')->name('scholar.')->group(function () {
    Route::get('/dashboard', [ScholarController::class, 'dashboard'])->name('dashboard');
    Route::get('/notifications', [ScholarController::class, 'notifications'])->name('notifications');
    
    // Notification routes
    Route::get('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::get('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');

    // Scholar profile routes
    Route::get('/profile', [ScholarProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ScholarProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ScholarProfileController::class, 'update'])->name('profile.update');

    // Settings and password change routes
    Route::get('/settings', [ScholarProfileController::class, 'showSettings'])->name('settings');
    Route::get('/password/change', [ScholarProfileController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/update', [ScholarProfileController::class, 'changePassword'])->name('password.update');

    // Scholar-specific fund request routes
    Route::get('/fund-requests', [FundRequestController::class, 'scholarIndex'])->name('fund-requests');
    Route::get('/fund-requests/index', [FundRequestController::class, 'scholarIndex'])->name('fund-requests.index');
    Route::get('/fund-requests/create', [FundRequestController::class, 'create'])->name('fund-requests.create');
    Route::post('/fund-requests', [FundRequestController::class, 'store'])->name('fund-requests.store');
    Route::get('/fund-requests/{id}', [FundRequestController::class, 'show'])->name('fund-requests.show');
    Route::get('/fund-requests/{id}/edit', [FundRequestController::class, 'edit'])->name('fund-requests.edit');
    Route::put('/fund-requests/{id}', [FundRequestController::class, 'update'])->name('fund-requests.update');
    Route::put('/fund-requests/{id}/submit', [FundRequestController::class, 'submit'])->name('fund-requests.submit');
    Route::delete('/fund-requests/{id}', [FundRequestController::class, 'destroy'])->name('fund-requests.destroy');



    // Scholar-specific manuscript routes
    Route::get('/manuscripts', [ManuscriptController::class, 'scholarIndex'])->name('manuscripts.index');
    Route::get('/manuscripts/create', [ManuscriptController::class, 'scholarCreate'])->name('manuscripts.create');
    Route::post('/manuscripts', [ManuscriptController::class, 'scholarStore'])->name('manuscripts.store');
    Route::get('/manuscripts/{id}', [ManuscriptController::class, 'scholarShow'])->name('manuscripts.show');
    Route::get('/manuscripts/{id}/edit', [ManuscriptController::class, 'scholarEdit'])->name('manuscripts.edit');
    Route::put('/manuscripts/{id}', [ManuscriptController::class, 'scholarUpdate'])->name('manuscripts.update');
    Route::delete('/manuscripts/{id}', [ManuscriptController::class, 'scholarDestroy'])->name('manuscripts.destroy');
    Route::put('/manuscripts/{id}/submit', [ManuscriptController::class, 'scholarSubmit'])->name('manuscripts.submit');
    
    // Scholar-specific document routes
    Route::get('/documents', [DocumentController::class, 'scholarIndex'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'scholarCreate'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'scholarStore'])->name('documents.store');
    Route::post('/documents/ajax-upload', [DocumentController::class, 'ajaxUpload'])->name('documents.ajax-upload');
    Route::get('/documents/json', [DocumentController::class, 'scholarFilesJson'])->name('documents.json');
    Route::get('/documents/{id}', [DocumentController::class, 'scholarShow'])->name('documents.show');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
});

// Admin routes - only accessible to admins
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Redis cached dashboard example
    Route::get('/cached-dashboard', [\App\Http\Controllers\CachedDashboardController::class, 'index'])->name('cached-dashboard');
    Route::post('/clear-cache', [\App\Http\Controllers\CachedDashboardController::class, 'clearCache'])->name('clear-cache');

    // Admin analytics
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');

    // Admin scholar management
    Route::get('/scholars', [ScholarController::class, 'index'])->name('scholars.index');
    Route::get('/scholars/create', [ScholarController::class, 'create'])->name('scholars.create');
    Route::post('/scholars', [ScholarController::class, 'store'])->name('scholars.store');
    Route::get('/scholars/{id}', [ScholarController::class, 'show'])->name('scholars.show');
    Route::get('/scholars/{id}/edit', [ScholarController::class, 'edit'])->name('scholars.edit');
    Route::put('/scholars/{id}', [ScholarController::class, 'update'])->name('scholars.update');
    Route::delete('/scholars/{id}', [ScholarController::class, 'destroy'])->name('scholars.destroy');

    // Admin fund request management
    Route::get('/fund-requests', [FundRequestController::class, 'adminIndex'])->name('fund-requests.index');
    Route::get('/fund-requests/filter', [FundRequestController::class, 'ajaxFilter'])->name('fund-requests.filter');
    Route::get('/fund-requests/{id}/documents', [FundRequestController::class, 'getDocuments'])->name('fund-requests.documents');
    Route::get('/fund-requests/{id}', [FundRequestController::class, 'show'])->name('fund-requests.show');
    Route::put('/fund-requests/{id}/approve', [FundRequestController::class, 'approve'])->name('fund-requests.approve');
    Route::put('/fund-requests/{id}/reject', [FundRequestController::class, 'reject'])->name('fund-requests.reject');
    
    // Document management routes
    Route::get('/documents', [DocumentController::class, 'adminIndex'])->name('documents.index');
    Route::get('/documents/{id}', [DocumentController::class, 'adminShow'])->name('documents.show');
    Route::put('/documents/{id}/verify', [DocumentController::class, 'verify'])->name('documents.verify');
    Route::put('/documents/{id}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');



    // Admin manuscript management
    Route::get('/manuscripts', [ManuscriptController::class, 'adminIndex'])->name('manuscripts.index');
    Route::get('/manuscripts/filter', [ManuscriptController::class, 'ajaxFilter'])->name('manuscripts.filter');
    Route::get('/manuscripts/export', [ManuscriptController::class, 'export'])->name('manuscripts.export');
    Route::get('/manuscripts/create', [ManuscriptController::class, 'create'])->name('manuscripts.create');
    Route::post('/manuscripts', [ManuscriptController::class, 'store'])->name('manuscripts.store');
    Route::get('/manuscripts/{id}', [ManuscriptController::class, 'show'])->name('manuscripts.show');
    Route::get('/manuscripts/{id}/edit', [ManuscriptController::class, 'edit'])->name('manuscripts.edit');
    Route::put('/manuscripts/{id}', [ManuscriptController::class, 'update'])->name('manuscripts.update');
    Route::put('/manuscripts/{id}/approve', [ManuscriptController::class, 'approve'])->name('manuscripts.approve');
    Route::put('/manuscripts/{id}/update-status/{status}', [ManuscriptController::class, 'updateStatus'])->name('manuscripts.update-status');
    Route::put('/manuscripts/{id}/update-status-notes', [ManuscriptController::class, 'updateStatusAndNotes'])->name('manuscripts.update-status-notes');
    Route::delete('/manuscripts/{id}', [ManuscriptController::class, 'destroy'])->name('manuscripts.destroy');

    // Admin reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

    // Admin audit logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');
    Route::get('/audit-logs/{id}', [AuditLogController::class, 'show'])->name('audit-logs.show');

    // Admin User Management Routes
    Route::resource('users', UserController::class);
});

// Profile routes (for both admin and scholar users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Catch-all route to redirect users to appropriate dashboard
Route::get('/dashboard', function() {
    if (Auth::user()->role === 'scholar') {
        return redirect()->route('scholar.dashboard');
    }
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');

// Home route as an alias to dashboard for consistency
Route::get('/home', function() {
    if (Auth::user()->role === 'scholar') {
        return redirect()->route('scholar.dashboard');
    }
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('home');
