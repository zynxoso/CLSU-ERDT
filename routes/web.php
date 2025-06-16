<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScholarController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
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
use App\Http\Controllers\PublicPageController;

// Redirect to home page
Route::get('/', function () {
    return view('index');
})->name('home');

// Scholar Login Route
Route::get('/scholar-login', function () {
    return view('auth.scholar-login');
})->name('scholar-login');

// Admin/SuperAdmin Login Route
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/faculty', [PublicPageController::class, 'faculty'])->name('faculty');

// Include authentication routes
require __DIR__.'/auth.php';

// Public marketing/info pages
Route::get('/how-to-apply', [PublicPageController::class, 'howToApply'])->name('how-to-apply');

Route::get('/about', [PublicPageController::class, 'about'])->name('about');

Route::get('/history', [PublicPageController::class, 'history'])->name('history');

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
    Route::post('/fund-requests/status-updates', [FundRequestController::class, 'getStatusUpdates'])->name('fund-requests.status-updates');
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

    // Admin password change routes
    Route::get('/password/change', [AdminController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/update', [AdminController::class, 'changePassword'])->name('password.update');

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
    Route::put('/fund-requests/{id}/under-review', [FundRequestController::class, 'markAsUnderReview'])->name('fund-requests.under-review');

    // Document management routes
    Route::get('/documents', [DocumentController::class, 'adminIndex'])->name('documents.index');
    Route::get('/documents/{id}', [DocumentController::class, 'adminShow'])->name('documents.show');
    Route::put('/documents/{id}/verify', [DocumentController::class, 'verify'])->name('documents.verify');
    Route::put('/documents/{id}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Admin manuscript management
    Route::get('/manuscripts', [\App\Http\Controllers\Admin\ManuscriptController::class, 'index'])->name('manuscripts.index');
    Route::get('/manuscripts/filter', [\App\Http\Controllers\Admin\ManuscriptController::class, 'filter'])->name('manuscripts.filter');
    Route::get('/manuscripts/export', [\App\Http\Controllers\Admin\ManuscriptController::class, 'export'])->name('manuscripts.export');
    Route::get('/manuscripts/batch-download/{batchId}/{file}', [\App\Http\Controllers\Admin\ManuscriptController::class, 'batchDownloadFile'])->name('manuscripts.batch-file')->where('file', '.*');
    Route::get('/manuscripts/create', [\App\Http\Controllers\Admin\ManuscriptController::class, 'create'])->name('manuscripts.create');
    Route::post('/manuscripts', [\App\Http\Controllers\Admin\ManuscriptController::class, 'store'])->name('manuscripts.store');
    Route::get('/manuscripts/{id}', [\App\Http\Controllers\Admin\ManuscriptController::class, 'show'])->name('manuscripts.show');
    Route::get('/manuscripts/{id}/edit', [\App\Http\Controllers\Admin\ManuscriptController::class, 'edit'])->name('manuscripts.edit');
    Route::put('/manuscripts/{id}', [\App\Http\Controllers\Admin\ManuscriptController::class, 'update'])->name('manuscripts.update');
    Route::delete('/manuscripts/{id}', [\App\Http\Controllers\Admin\ManuscriptController::class, 'destroy'])->name('manuscripts.destroy');

    // Admin reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

    // Admin audit logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');
    Route::get('/audit-logs/{id}', [AuditLogController::class, 'show'])->name('audit-logs.show');

    // Admin User Management Routes
    Route::resource('users', UserController::class);

    // Application Timeline Management Routes
    Route::resource('application-timeline', \App\Http\Controllers\Admin\ApplicationTimelineController::class);
    Route::patch('application-timeline/{applicationTimeline}/toggle-status', [\App\Http\Controllers\Admin\ApplicationTimelineController::class, 'toggleStatus'])
        ->name('application-timeline.toggle-status');

    // Important Notes Management Routes
    Route::resource('important-notes', \App\Http\Controllers\Admin\ImportantNoteController::class);
    Route::patch('important-notes/{importantNote}/toggle-status', [\App\Http\Controllers\Admin\ImportantNoteController::class, 'toggleStatus'])
        ->name('important-notes.toggle-status');

    // History Management Routes
    Route::resource('history-timeline', \App\Http\Controllers\Admin\HistoryTimelineController::class);
    Route::patch('history-timeline/{historyTimeline}/toggle-status', [\App\Http\Controllers\Admin\HistoryTimelineController::class, 'toggleStatus'])
        ->name('history-timeline.toggle-status');

    Route::resource('history-achievements', \App\Http\Controllers\Admin\HistoryAchievementController::class);
    Route::patch('history-achievements/{historyAchievement}/toggle-status', [\App\Http\Controllers\Admin\HistoryAchievementController::class, 'toggleStatus'])
        ->name('history-achievements.toggle-status');

    Route::resource('history-content', \App\Http\Controllers\Admin\HistoryContentController::class);
    Route::patch('history-content/{historyContent}/toggle-status', [\App\Http\Controllers\Admin\HistoryContentController::class, 'toggleStatus'])
        ->name('history-content.toggle-status');
});

// Profile routes (for both admin and scholar users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Super Admin routes - only accessible to super admins
Route::middleware(['auth', \App\Http\Middleware\SuperAdminMiddleware::class])->prefix('super-admin')->name('super_admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/user-management', [SuperAdminController::class, 'userManagement'])->name('user_management');
    Route::get('/user-management/{id}/edit', [SuperAdminController::class, 'editUser'])->name('user_management.edit');
    Route::put('/user-management/{id}', [SuperAdminController::class, 'updateUser'])->name('user_management.update');
    Route::get('/system-settings', [SuperAdminController::class, 'systemSettings'])->name('system_settings');
    Route::get('/system-configuration', [SuperAdminController::class, 'systemConfiguration'])->name('system_configuration');
    Route::get('/data-management', [SuperAdminController::class, 'dataManagement'])->name('data_management');
    Route::get('/website-management', [SuperAdminController::class, 'websiteManagement'])->name('website_management');

    // Application Timeline Management Routes
    Route::get('/application-timeline', [SuperAdminController::class, 'applicationTimeline'])->name('application_timeline');
    Route::get('/application-timeline/create', [SuperAdminController::class, 'createTimelineItem'])->name('application_timeline.create');
    Route::post('/application-timeline', [SuperAdminController::class, 'storeTimelineItem'])->name('application_timeline.store');
    Route::get('/application-timeline/{id}/edit', [SuperAdminController::class, 'editTimelineItem'])->name('application_timeline.edit');
    Route::put('/application-timeline/{id}', [SuperAdminController::class, 'updateTimelineItem'])->name('application_timeline.update');
    Route::delete('/application-timeline/{id}', [SuperAdminController::class, 'deleteTimelineItem'])->name('application_timeline.delete');
    Route::patch('/application-timeline/{id}/toggle-status', [SuperAdminController::class, 'toggleTimelineStatus'])->name('application_timeline.toggle_status');

    // Announcement Management Routes
    Route::post('/announcements', [SuperAdminController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::put('/announcements/{id}', [SuperAdminController::class, 'updateAnnouncement'])->name('announcements.update');
    Route::delete('/announcements/{id}', [SuperAdminController::class, 'deleteAnnouncement'])->name('announcements.delete');
    Route::patch('/announcements/{id}/toggle-status', [SuperAdminController::class, 'toggleAnnouncementStatus'])->name('announcements.toggle_status');

    // Faculty Management Routes
    Route::post('/faculty', [SuperAdminController::class, 'storeFaculty'])->name('faculty.store');
    Route::put('/faculty/{id}', [SuperAdminController::class, 'updateFaculty'])->name('faculty.update');
    Route::delete('/faculty/{id}', [SuperAdminController::class, 'deleteFaculty'])->name('faculty.delete');
    Route::patch('/faculty/{id}/toggle-status', [SuperAdminController::class, 'toggleFacultyStatus'])->name('faculty.toggle_status');

    // Test repository routes - for testing the repository pattern implementation
    Route::get('/test-user-repository', [\App\Http\Controllers\TestRepositoryController::class, 'testUserRepository'])->name('test_user_repository');
    Route::get('/test-fund-request-repository', [\App\Http\Controllers\TestRepositoryController::class, 'testFundRequestRepository'])->name('test_fund_request_repository');
});

// Catch-all route to redirect users to appropriate dashboard
Route::get('/dashboard', function() {
    if (Auth::user()->role === 'scholar') {
        return redirect()->route('scholar.dashboard');
    } elseif (Auth::user()->role === 'super_admin') {
        return redirect()->route('super_admin.dashboard');
    }
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');

// Example routes for demonstrating exception handling
Route::prefix('example')->name('example.')->group(function () {
    Route::get('/exceptions', [\App\Http\Controllers\ExampleExceptionController::class, 'index'])->name('exceptions');
    Route::get('/bad-request', [\App\Http\Controllers\ExampleExceptionController::class, 'badRequest'])->name('bad-request');
    Route::get('/validation-error', [\App\Http\Controllers\ExampleExceptionController::class, 'validationError'])->name('validation-error');
    Route::get('/unauthorized', [\App\Http\Controllers\ExampleExceptionController::class, 'unauthorized'])->name('unauthorized');
    Route::get('/forbidden', [\App\Http\Controllers\ExampleExceptionController::class, 'forbidden'])->name('forbidden');
    Route::get('/not-found', [\App\Http\Controllers\ExampleExceptionController::class, 'notFound'])->name('not-found');
    Route::get('/server-error', [\App\Http\Controllers\ExampleExceptionController::class, 'serverError'])->name('server-error');
});


