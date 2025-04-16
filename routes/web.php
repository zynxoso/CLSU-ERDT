<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScholarController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FundRequestController;
use App\Http\Controllers\DisbursementController;
use App\Http\Controllers\ManuscriptController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScholarFundRequestController;
use App\Http\Controllers\ScholarProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;

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

    // Scholar profile routes
    Route::get('/profile', [ScholarProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ScholarProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ScholarProfileController::class, 'update'])->name('profile.update');

    // Password change routes
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

    // Scholar-specific document routes
    Route::get('/documents', [DocumentController::class, 'scholarIndex'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'scholarCreate'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'scholarStore'])->name('documents.store');
    Route::get('/documents/{id}', [DocumentController::class, 'scholarShow'])->name('documents.show');
    Route::get('/documents/{id}/edit', [DocumentController::class, 'scholarEdit'])->name('documents.edit');
    Route::put('/documents/{id}', [DocumentController::class, 'scholarUpdate'])->name('documents.update');
    Route::get('/documents/{id}/download', [DocumentController::class, 'scholarDownload'])->name('documents.download');

    // Scholar-specific manuscript routes
    Route::get('/manuscripts', [ManuscriptController::class, 'scholarIndex'])->name('manuscripts.index');
    Route::get('/manuscripts/create', [ManuscriptController::class, 'scholarCreate'])->name('manuscripts.create');
    Route::post('/manuscripts', [ManuscriptController::class, 'scholarStore'])->name('manuscripts.store');
    Route::get('/manuscripts/{id}', [ManuscriptController::class, 'scholarShow'])->name('manuscripts.show');
    Route::get('/manuscripts/{id}/edit', [ManuscriptController::class, 'scholarEdit'])->name('manuscripts.edit');
    Route::put('/manuscripts/{id}', [ManuscriptController::class, 'scholarUpdate'])->name('manuscripts.update');
    Route::delete('/manuscripts/{id}', [ManuscriptController::class, 'scholarDestroy'])->name('manuscripts.destroy');
    Route::put('/manuscripts/{id}/submit', [ManuscriptController::class, 'scholarSubmit'])->name('manuscripts.submit');
});

// Admin routes - only accessible to admins
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Admin analytics
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');

    // Admin scholar management
    Route::get('/scholars', [ScholarController::class, 'index'])->name('scholars.index');
    Route::get('/scholars/filter', [ScholarController::class, 'ajaxFilter'])->name('scholars.filter');
    Route::get('/scholars/create', [ScholarController::class, 'create'])->name('scholars.create');
    Route::post('/scholars', [ScholarController::class, 'store'])->name('scholars.store');
    Route::get('/scholars/{id}', [ScholarController::class, 'show'])->name('scholars.show');
    Route::get('/scholars/{id}/edit', [ScholarController::class, 'edit'])->name('scholars.edit');
    Route::put('/scholars/{id}', [ScholarController::class, 'update'])->name('scholars.update');
    Route::delete('/scholars/{id}', [ScholarController::class, 'destroy'])->name('scholars.destroy');

    // Admin fund request management
    Route::get('/fund-requests', [FundRequestController::class, 'adminIndex'])->name('fund-requests.index');
    Route::get('/fund-requests/filter', [FundRequestController::class, 'ajaxFilter'])->name('fund-requests.filter');
    Route::get('/fund-requests/{id}', [FundRequestController::class, 'show'])->name('fund-requests.show');
    Route::put('/fund-requests/{id}/approve', [FundRequestController::class, 'approve'])->name('fund-requests.approve');
    Route::put('/fund-requests/{id}/reject', [FundRequestController::class, 'reject'])->name('fund-requests.reject');

    // Admin document management
    Route::get('/documents', [DocumentController::class, 'adminIndex'])->name('documents.index');
    Route::get('/documents/filter', [DocumentController::class, 'ajaxFilter'])->name('documents.filter');
    Route::get('/documents/{id}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{id}/download', [DocumentController::class, 'scholarDownload'])->name('documents.download');
    Route::put('/documents/{id}/verify', [DocumentController::class, 'verify'])->name('documents.verify');
    Route::put('/documents/{id}/reject', [DocumentController::class, 'reject'])->name('documents.reject');

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
    Route::delete('/manuscripts/{id}', [ManuscriptController::class, 'destroy'])->name('manuscripts.destroy');

    // Admin reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/test-pdf', [ReportController::class, 'testPdf'])->name('reports.test-pdf');
    Route::get('/reports/test-manuscript-pdf', [ReportController::class, 'testManuscriptPdf'])->name('reports.test-manuscript-pdf');

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

// Debug route to check authentication and role
Route::get('/debug-auth', function() {
    $debug = [
        'authenticated' => Auth::check(),
        'user' => Auth::check() ? [
            'id' => Auth::id(),
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'role' => Auth::user()->role,
            'created_at' => Auth::user()->created_at,
        ] : null,
        'session_id' => session()->getId(),
        'cookies' => request()->cookies->all(),
    ];

    // Log this information
    \Illuminate\Support\Facades\Log::info('Debug Auth Check', $debug);

    return response()->json($debug);
});

// Debug route to run scholar debug command
Route::get('/debug-scholars', function() {
    // Only allow in local/development environment
    if (!app()->environment('local') && !app()->environment('development')) {
        abort(403, 'This route is only available in development environment.');
    }

    // Run the command
    \Illuminate\Support\Facades\Artisan::call('debug:scholars');

    // Get command output
    $output = \Illuminate\Support\Facades\Artisan::output();

    // Format for browser
    $formattedOutput = nl2br(htmlentities($output));

    return response()->view('debug.output', [
        'title' => 'Scholar Debug Output',
        'output' => $formattedOutput,
        'raw' => $output
    ]);
});

// Test route to create a scholar directly
Route::get('/test-create-scholar', function() {
    // Only allow in local/development environment
    if (!app()->environment('local') && !app()->environment('development')) {
        abort(403, 'This route is only available in development environment.');
    }

    // Check if we're logged in as admin
    if (!\Illuminate\Support\Facades\Auth::check() || \Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
        return response()->json([
            'success' => false,
            'error' => 'You must be logged in as admin to test this function',
            'user' => \Illuminate\Support\Facades\Auth::check() ? [
                'id' => \Illuminate\Support\Facades\Auth::id(),
                'name' => \Illuminate\Support\Facades\Auth::user()->name,
                'role' => \Illuminate\Support\Facades\Auth::user()->role
            ] : null
        ]);
    }

    $output = "Starting scholar creation test...\n";

    // Transaction for safety
    \Illuminate\Support\Facades\DB::beginTransaction();

    try {
        // Create a test user
        $user = \App\Models\User::create([
            'name' => 'Test Scholar ' . uniqid(),
            'email' => 'test_' . time() . '@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'scholar',
        ]);

        $output .= "User created with ID: " . $user->id . "\n";

        // Create a basic scholar profile
        $scholar = new \App\Models\ScholarProfile();
        $scholar->user_id = $user->id;
        $scholar->first_name = 'Test';
        $scholar->last_name = 'Scholar';
        $scholar->contact_number = '12345678901';
        $scholar->address = 'Test Address';
        $scholar->university = 'Central Luzon State University';
        $scholar->program = 'Master in Agricultural and Biosystems Engineering';
        $scholar->department = 'Engineering';
        $scholar->status = 'New';
        $scholar->start_date = \Carbon\Carbon::now();
        $scholar->expected_completion_date = \Carbon\Carbon::now()->addYears(2);
        $scholar->save();

        $output .= "Scholar profile created with ID: " . $scholar->id . "\n";

        // Commit if everything was successful
        \Illuminate\Support\Facades\DB::commit();
        $output .= "SUCCESS: Transaction committed\n";

    } catch (\Exception $e) {
        // Rollback if there was an error
        \Illuminate\Support\Facades\DB::rollBack();
        $output .= "ERROR: " . $e->getMessage() . "\n";
        $output .= "Stack trace: " . $e->getTraceAsString() . "\n";
    }

    // Format for browser display
    $formattedOutput = nl2br(htmlentities($output));

    return response()->view('debug.output', [
        'title' => 'Scholar Creation Test',
        'output' => $formattedOutput,
        'raw' => $output
    ]);
});

// Direct API endpoint for scholar form post debugging
Route::post('/debug-scholar-form', function(\Illuminate\Http\Request $request) {
    $logPath = storage_path('logs/debug_scholar_form.log');
    $logMessage = "Form data received at " . now() . "\n";

    // Ensure log directory exists
    if (!file_exists(storage_path('logs'))) {
        mkdir(storage_path('logs'), 0755, true);
    }

    // Log authentication info
    $logMessage .= "Auth status: " . (\Illuminate\Support\Facades\Auth::check() ? 'Authenticated' : 'Not authenticated') . "\n";
    if (\Illuminate\Support\Facades\Auth::check()) {
        $logMessage .= "User ID: " . \Illuminate\Support\Facades\Auth::id() . "\n";
        $logMessage .= "User Email: " . \Illuminate\Support\Facades\Auth::user()->email . "\n";
        $logMessage .= "User Role: " . \Illuminate\Support\Facades\Auth::user()->role . "\n";
    }

    // Log form data
    $logMessage .= "Form data: " . json_encode($request->except(['password', '_token'])) . "\n";
    $logMessage .= "Headers: " . json_encode($request->headers->all()) . "\n";

    // Log CSRF token info
    $logMessage .= "Session CSRF token: " . $request->session()->token() . "\n";
    $logMessage .= "Form CSRF token: " . $request->input('_token') . "\n";
    $logMessage .= "Token match: " . ($request->session()->token() === $request->input('_token') ? 'Yes' : 'No') . "\n";

    file_put_contents($logPath, $logMessage, FILE_APPEND);

    // Return response
    return response()->json([
        'success' => true,
        'message' => 'Form data received and logged',
        'auth' => \Illuminate\Support\Facades\Auth::check(),
        'user' => \Illuminate\Support\Facades\Auth::check() ? [
            'id' => \Illuminate\Support\Facades\Auth::id(),
            'role' => \Illuminate\Support\Facades\Auth::user()->role
        ] : null,
        'form_data' => $request->except(['password', '_token']),
        'csrf_match' => $request->session()->token() === $request->input('_token')
    ]);
});
