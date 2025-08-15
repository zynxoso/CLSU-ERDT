<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ScholarController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\FundRequestController;
use App\Http\Controllers\ManuscriptController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScholarProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StipendController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\DownloadableFormController;


// Redirect to home page
Route::get('/', function () {
    return view('index');
})->name('home');

// Scholar Login Route is handled in auth.php
// Admin/SuperAdmin Login Route is handled in auth.php



// Include authentication routes
require __DIR__.'/auth.php';

// Backward compatibility redirects for old login URLs
Route::get('/login', function () {
    return redirect('/CLSU-ERDT-COORDINATOR', 301);
});

Route::get('/scholar-login', function () {
    return redirect('/CLSU-ERDT-SCHOLARSHIP', 301);
});

Route::post('/login', function () {
    return redirect('/CLSU-ERDT-COORDINATOR', 301);
});

Route::post('/scholar-login', function () {
    return redirect('/CLSU-ERDT-SCHOLARSHIP', 301);
});

// Public marketing/info pages
Route::get('/how-to-apply', [PublicPageController::class, 'howToApply'])->name('how-to-apply');

Route::get('/about', [PublicPageController::class, 'about'])->name('about');

Route::get('/history', [PublicPageController::class, 'history'])->name('history');

// Scholar routes - only accessible to scholars
Route::middleware(['auth:scholar'])->prefix('scholar')->name('scholar.')->group(function () {
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
    Route::get('/fund-requests/existing-types', [FundRequestController::class, 'getExistingRequestTypes'])->name('fund-requests.existing-types');
    Route::post('/fund-requests/validate-amount', [FundRequestController::class, 'validateAmount'])->name('fund-requests.validate-amount');
    Route::post('/fund-requests/pre-validate', [FundRequestController::class, 'preValidate'])->name('fund-requests.pre-validate');
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
    Route::post('/documents', [DocumentController::class, 'scholarStore'])->name('documents.store')->middleware('api.rate.limit:upload');
    Route::post('/documents/ajax-upload', [DocumentController::class, 'ajaxUpload'])->name('documents.ajax-upload')->middleware('api.rate.limit:upload');
    Route::get('/documents/json', [DocumentController::class, 'scholarFilesJson'])->name('documents.json')->middleware('api.rate.limit:ajax');
    Route::get('/documents/{id}', [DocumentController::class, 'scholarShow'])->name('documents.show');
    Route::get('/documents/{id}/view', [DocumentController::class, 'view'])->name('documents.view');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Notification AJAX routes (with rate limiting) - these override the basic routes above for AJAX calls
    Route::get('/notifications/mark-as-read-ajax/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read-ajax')->middleware('api.rate.limit:ajax');
    Route::get('/notifications/mark-all-as-read-ajax', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read-ajax')->middleware('api.rate.limit:ajax');

    // Scholar status updates endpoint (moved from API routes for proper session auth)
    Route::get('/status-updates', [\App\Http\Controllers\Scholar\StatusUpdateController::class, 'index'])->name('status-updates')->middleware('api.rate.limit:ajax');
});

// Admin routes - only accessible to admins
Route::middleware(['auth:web', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');



    // Admin password change routes
    Route::get('/password/change', [AdminController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/update', [AdminController::class, 'changePassword'])->name('password.update');

    // Admin profile routes
    Route::get('/profile/edit', [AdminController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [AdminController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/notifications', [AdminController::class, 'updateNotifications'])->name('profile.notifications');

    // Admin settings routes
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/scholarship/update', [AdminController::class, 'updateScholarshipSettings'])->name('settings.scholarship.update');

    // Admin scholar management
    Route::get('/scholars', [ScholarController::class, 'index'])->name('scholars.index');
    Route::get('/scholars/create', [ScholarController::class, 'create'])->name('scholars.create');
    Route::post('/scholars', [ScholarController::class, 'store'])->name('scholars.store');
    Route::get('/scholars/{id}', [ScholarController::class, 'show'])->name('scholars.show');
    Route::get('/scholars/{id}/edit', [ScholarController::class, 'edit'])->name('scholars.edit');
    Route::put('/scholars/{id}', [ScholarController::class, 'update'])->name('scholars.update');
    Route::delete('/scholars/{id}', [ScholarController::class, 'destroy'])->name('scholars.destroy');

    // Admin scholar password management
    Route::get('/scholars/{id}/change-password', [ScholarController::class, 'showChangePasswordForm'])->name('scholars.change-password');
    Route::put('/scholars/{id}/change-password', [ScholarController::class, 'changePassword'])->name('scholars.update-password');

    // Admin fund request management
    Route::get('/fund-requests', [FundRequestController::class, 'adminIndex'])->name('fund-requests.index');
    Route::post('/fund-requests/check-duplicates', [FundRequestController::class, 'checkDuplicates'])->name('fund-requests.check-duplicates');
    Route::post('/fund-requests/pre-validate', [FundRequestController::class, 'adminPreValidate'])->name('fund-requests.admin-pre-validate');

    Route::get('/fund-requests/{id}/documents', [FundRequestController::class, 'getDocuments'])->name('fund-requests.documents')->middleware('api.rate.limit:ajax');
    Route::get('/fund-requests/{id}', [FundRequestController::class, 'show'])->name('fund-requests.show');
    Route::get('/fund-requests/{id}/edit', [FundRequestController::class, 'adminEdit'])->name('fund-requests.edit');
    Route::put('/fund-requests/{id}', [FundRequestController::class, 'adminUpdate'])->name('fund-requests.update');
    Route::put('/fund-requests/{id}/approve', [FundRequestController::class, 'approve'])->name('fund-requests.approve')->middleware('api.rate.limit:sensitive');
    Route::put('/fund-requests/{id}/reject', [FundRequestController::class, 'reject'])->name('fund-requests.reject')->middleware('api.rate.limit:sensitive');
    Route::put('/fund-requests/{id}/under-review', [FundRequestController::class, 'markAsUnderReview'])->name('fund-requests.under-review')->middleware('api.rate.limit:admin');

    // Document management routes
    Route::get('/documents/{id}/view', [DocumentController::class, 'view'])->name('documents.view');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');


    // Admin manuscript management
    Route::get('/manuscripts', [\App\Http\Controllers\Admin\ManuscriptController::class, 'index'])->name('manuscripts.index');
    Route::get('/manuscripts/create', [\App\Http\Controllers\Admin\ManuscriptController::class, 'create'])->name('manuscripts.create');
    Route::post('/manuscripts', [\App\Http\Controllers\Admin\ManuscriptController::class, 'store'])->name('manuscripts.store');
    Route::get('/manuscripts/export', [\App\Http\Controllers\Admin\ManuscriptController::class, 'export'])->name('manuscripts.export')->middleware('api.rate.limit:export');
    Route::get('/manuscripts/batch-download/{batchId}/{file}', [\App\Http\Controllers\Admin\ManuscriptController::class, 'batchDownloadFile'])->name('manuscripts.batch-file')->where('file', '.*')->middleware('api.rate.limit:export');
    Route::get('/manuscripts/{id}', [\App\Http\Controllers\Admin\ManuscriptController::class, 'show'])->name('manuscripts.show');
    Route::put('/manuscripts/{id}/status', [\App\Http\Controllers\Admin\ManuscriptController::class, 'updateStatusAndNotes'])->name('manuscripts.updateStatus');

    // Admin reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate')->middleware('api.rate.limit:export');

    // Admin audit logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export')->middleware('api.rate.limit:export');

    // Admin notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');

    // Admin User Management Routes
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

    // Admin Stipend Notification Routes
    Route::get('/stipends', [StipendController::class, 'index'])->name('stipends.index');
    Route::get('/stipends/bulk-notify', [StipendController::class, 'showBulkNotify'])->name('stipends.bulk-notify');
    Route::post('/stipends/bulk-notify', [StipendController::class, 'sendBulkNotifications'])->name('stipends.bulk-notify.send')->middleware('api.rate.limit:sensitive');
    Route::get('/stipends/stats', [StipendController::class, 'getStats'])->name('stipends.stats')->middleware('api.rate.limit:ajax');



    // Content Management Routes
    Route::prefix('content-management')->name('content-management.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ContentManagementController::class, 'index'])->name('index');

        // Announcement routes
        Route::get('/announcements', [\App\Http\Controllers\Admin\ContentManagementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [\App\Http\Controllers\Admin\ContentManagementController::class, 'storeAnnouncement'])->name('announcements.store');
        Route::put('/announcements/{id}', [\App\Http\Controllers\Admin\ContentManagementController::class, 'updateAnnouncement'])->name('announcements.update');
        Route::delete('/announcements/{id}', [\App\Http\Controllers\Admin\ContentManagementController::class, 'destroyAnnouncement'])->name('announcements.destroy');
        Route::patch('/announcements/{id}/toggle-status', [\App\Http\Controllers\Admin\ContentManagementController::class, 'toggleAnnouncementStatus'])->name('announcements.toggle-status');

        // Faculty routes
        Route::get('/faculty', [\App\Http\Controllers\Admin\ContentManagementController::class, 'index'])->name('faculty.index');
        Route::post('/faculty', [\App\Http\Controllers\Admin\ContentManagementController::class, 'storeFaculty'])->name('faculty.store');
        Route::put('/faculty/{id}', [\App\Http\Controllers\Admin\ContentManagementController::class, 'updateFaculty'])->name('faculty.update');
        Route::delete('/faculty/{id}', [\App\Http\Controllers\Admin\ContentManagementController::class, 'destroyFaculty'])->name('faculty.destroy');
        Route::patch('/faculty/{id}/toggle-status', [\App\Http\Controllers\Admin\ContentManagementController::class, 'toggleFacultyStatus'])->name('faculty.toggle-status');

        // Form routes
        Route::get('/forms', [\App\Http\Controllers\Admin\ContentManagementController::class, 'index'])->name('forms.index');
        Route::post('/forms', [\App\Http\Controllers\Admin\ContentManagementController::class, 'storeForm'])->name('forms.store');
        Route::get('/forms/{id}/edit', [\App\Http\Controllers\Admin\ContentManagementController::class, 'editForm'])->name('forms.edit');
        Route::put('/forms/{id}', [\App\Http\Controllers\Admin\ContentManagementController::class, 'updateForm'])->name('forms.update');
        Route::delete('/forms/{id}', [\App\Http\Controllers\Admin\ContentManagementController::class, 'destroyForm'])->name('forms.destroy');
        Route::patch('/forms/{id}/toggle-status', [\App\Http\Controllers\Admin\ContentManagementController::class, 'toggleFormStatus'])->name('forms.toggle-status');

        // Timeline routes
        Route::get('/timelines', [\App\Http\Controllers\Admin\ContentManagementController::class, 'index'])->name('timelines.index');
        Route::post('/timelines', [\App\Http\Controllers\Admin\ContentManagementController::class, 'storeTimeline'])->name('timelines.store');
        Route::put('/timelines/{id}', [\App\Http\Controllers\Admin\ContentManagementController::class, 'updateTimeline'])->name('timelines.update');
        Route::delete('/timelines/{id}', [\App\Http\Controllers\Admin\ContentManagementController::class, 'destroyTimeline'])->name('timelines.destroy');
        Route::patch('/timelines/{id}/toggle-status', [\App\Http\Controllers\Admin\ContentManagementController::class, 'toggleTimelineStatus'])->name('timelines.toggle-status');
    });

    // Important Notes Management Routes
    Route::resource('important-notes', \App\Http\Controllers\Admin\ImportantNoteController::class);
    Route::patch('important-notes/{importantNote}/toggle-status', [\App\Http\Controllers\Admin\ImportantNoteController::class, 'toggleStatus'])
        ->name('important-notes.toggle-status');

    // History Management Routes

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

    // Profile management routes
    Route::get('/profile/edit', [SuperAdminController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [SuperAdminController::class, 'updateProfile'])->name('profile.update');
    Route::get('/password/change', [SuperAdminController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/update', [SuperAdminController::class, 'changePassword'])->name('password.update');

    Route::get('/user-management', [SuperAdminController::class, 'userManagement'])->name('user_management');
    Route::get('/user-management/{id}/edit', [SuperAdminController::class, 'editUser'])->name('user_management.edit');
    Route::put('/user-management/{id}', [SuperAdminController::class, 'updateUser'])->name('user_management.update');

    Route::get('/system-settings', [SuperAdminController::class, 'systemSettings'])->name('system_settings');
    Route::post('/system-settings/general', [SuperAdminController::class, 'updateGeneralSettings'])->name('system_settings.general');
    Route::post('/system-settings/email', [SuperAdminController::class, 'updateEmailSettings'])->name('system_settings.email');
    Route::post('/system-settings/security', [SuperAdminController::class, 'updateSecuritySettings'])->name('system_settings.security');
    Route::get('/system-configuration', [SuperAdminController::class, 'systemConfiguration'])->name('system_configuration');
    Route::post('/system-configuration/academic-calendar', [SuperAdminController::class, 'updateAcademicCalendar'])->name('system_configuration.academic_calendar');
    Route::post('/system-configuration/scholarship-parameters', [SuperAdminController::class, 'updateScholarshipParameters'])->name('system_configuration.scholarship_parameters');
    Route::get('/data-management', [SuperAdminController::class, 'dataManagement'])->name('data_management');


    // Application Timeline Management Routes
    Route::get('/application-timeline', [SuperAdminController::class, 'applicationTimeline'])->name('application_timeline');
    Route::post('/application-timeline', [SuperAdminController::class, 'storeTimelineItem'])->name('application_timeline.store');
    Route::get('/application-timeline/{id}/edit', [SuperAdminController::class, 'editTimelineItem'])->name('application_timeline.edit');
    Route::put('/application-timeline/{id}', [SuperAdminController::class, 'updateTimelineItem'])->name('application_timeline.update');

      Route::get('/notifications', function() {
        return view('super_admin.notifications.index');
    })->name('notifications.index');

    // Announcement Management Routes
    Route::post('/announcements', [SuperAdminController::class, 'storeAnnouncement'])->name('announcements.store')->middleware('api.rate.limit:sensitive');
    Route::put('/announcements/{id}', [SuperAdminController::class, 'updateAnnouncement'])->name('announcements.update')->middleware('api.rate.limit:sensitive');
    Route::delete('/announcements/{id}', [SuperAdminController::class, 'deleteAnnouncement'])->name('announcements.delete')->middleware('api.rate.limit:sensitive');

    // Faculty Management Routes
    Route::post('/faculty', [SuperAdminController::class, 'storeFaculty'])->name('faculty.store')->middleware('api.rate.limit:sensitive');
    Route::put('/faculty/{id}', [SuperAdminController::class, 'updateFaculty'])->name('faculty.update')->middleware('api.rate.limit:sensitive');
    Route::delete('/faculty/{id}', [SuperAdminController::class, 'deleteFaculty'])->name('faculty.delete')->middleware('api.rate.limit:sensitive');

    // Downloadable Forms Management
    Route::get('/downloadable-forms', [DownloadableFormController::class, 'index'])->name('downloadable-forms.index');
    Route::get('/downloadable-forms/create', [DownloadableFormController::class, 'create'])->name('downloadable-forms.create');
    Route::post('/downloadable-forms', [DownloadableFormController::class, 'store'])->name('downloadable-forms.store');
    Route::put('/downloadable-forms/{id}', [DownloadableFormController::class, 'update'])->name('downloadable-forms.update');
    Route::delete('/downloadable-forms/{id}', [DownloadableFormController::class, 'destroy'])->name('downloadable-forms.destroy');
    Route::patch('/downloadable-forms/{id}/toggle-status', [DownloadableFormController::class, 'toggleStatus'])->name('downloadable-forms.toggle-status');
    Route::post('/downloadable-forms/bulk-action', [DownloadableFormController::class, 'bulkAction'])->name('downloadable-forms.bulk-action');
});

// Public Downloadable Forms Routes
Route::get('/forms', [DownloadableFormController::class, 'index'])->name('downloadable-forms.public.index');
Route::get('/forms/{downloadableForm}', [DownloadableFormController::class, 'show'])->name('downloadable-forms.show');
Route::get('/forms/{downloadableForm}/download', [DownloadableFormController::class, 'download'])->name('downloadable-forms.download');



// Session Management Routes
Route::middleware(['auth'])->group(function () {
    // Extend session endpoint
    Route::post('/extend-session', function () {
        $sessionService = app(\App\Services\SessionManagementService::class);
        $sessionService->updateLastActivity();
        
        return response()->json([
            'success' => true,
            'message' => 'Session extended successfully',
            'last_activity' => time(),
            'session_status' => $sessionService->getSessionStatus()
        ]);
    })->name('session.extend');
    
    // Get session status
    Route::get('/session-status', function () {
        $sessionService = app(\App\Services\SessionManagementService::class);
        
        return response()->json($sessionService->getSessionStatus());
    })->name('session.status');
});

// Catch-all route to redirect users to appropriate dashboard - FIXED
Route::get('/dashboard', function() {
    // Check if user is authenticated before accessing role
    if (!Auth::check()) {
        return redirect()->route('home');
    }
    
    $user = Auth::user();
    
    if ($user->role === 'scholar') {
        return redirect()->route('scholar.dashboard');
    } elseif ($user->role === 'super_admin') {
        return redirect()->route('super_admin.dashboard');
    }
    
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');


