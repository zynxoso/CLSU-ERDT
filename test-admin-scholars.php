<?php

require __DIR__.'/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Initialize the application
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\ScholarProfile;
use Illuminate\Support\Facades\DB;

echo "\n=================================================================\n";
echo "             ADMIN SCHOLAR MANAGEMENT TEST\n";
echo "=================================================================\n\n";

// 1. Check if test admin exists
$admin = User::where('email', 'admin@clsu-erdt.edu.ph')->first();
if ($admin) {
    echo "✅ TEST ADMIN USER\n";
    echo "   ID: {$admin->id}\n";
    echo "   Name: {$admin->name}\n";
    echo "   Email: {$admin->email}\n";
    echo "   Role: {$admin->role}\n";
} else {
    echo "❌ Test admin does not exist in the database\n";
    exit(1);
}

echo "\n-----------------------------------------------------------------\n";

// 2. Check if scholars exist in the database
$scholars = ScholarProfile::with('user')->get();
echo "✅ SCHOLAR PROFILES: " . count($scholars) . " total\n\n";

// Display the first 3 scholars
if (count($scholars) > 0) {
    echo "   Sample Scholar Profiles:\n";
    foreach ($scholars->take(3) as $index => $scholar) {
        echo "     #{$index} ID: {$scholar->id}\n";
        echo "        Name: {$scholar->first_name} {$scholar->last_name}\n";
        echo "        University: {$scholar->university}\n";
        echo "        Program: {$scholar->program}\n";
        echo "        Status: {$scholar->status}\n";
        echo "        User: " . ($scholar->user ? "{$scholar->user->name} ({$scholar->user->email})" : "Missing ✗") . "\n";

        if ($index < 2 && $index < $scholars->count() - 1) echo "\n";
    }
}

echo "\n-----------------------------------------------------------------\n";

// 3. Test admin login
if (Auth::attempt(['email' => 'admin@clsu-erdt.edu.ph', 'password' => 'password'])) {
    echo "✅ ADMIN LOGIN TEST\n";
    echo "   Current user: " . Auth::user()->name . "\n";
    echo "   Role: " . Auth::user()->role . "\n";

    // Check admin middleware functionality
    $user = Auth::user();
    $isAdmin = $user->role === 'admin';
    echo "   Admin role check: " . ($isAdmin ? "Passed ✓" : "Failed ✗") . "\n";

    echo "\n   Admin Scholar Management Routes:\n";
    // Get all admin scholar routes
    $adminScholarRoutes = collect(Route::getRoutes())->filter(function($route) {
        return strpos($route->getName(), 'admin.scholars.') === 0;
    })->map(function($route) {
        return $route->getName();
    });

    foreach ($adminScholarRoutes as $route) {
        echo "     • $route\n";
    }

    // Logout
    Auth::logout();
    echo "\n   Logout successful ✓\n";
} else {
    echo "❌ Login failed for admin@clsu-erdt.edu.ph\n";
}

echo "\n-----------------------------------------------------------------\n";

// 4. Verify ScholarController functionality
try {
    $scholarController = app()->make('App\Http\Controllers\ScholarController');
    echo "✅ SCHOLAR CONTROLLER\n";
    echo "   Controller exists and can be instantiated\n";

    // Check if required methods exist
    $requiredMethods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
    $missingMethods = [];

    foreach ($requiredMethods as $method) {
        if (!method_exists($scholarController, $method)) {
            $missingMethods[] = $method;
        }
    }

    if (empty($missingMethods)) {
        echo "   All required methods exist ✓\n";
    } else {
        echo "   Missing methods: " . implode(', ', $missingMethods) . " ✗\n";
    }
} catch (\Exception $e) {
    echo "❌ SCHOLAR CONTROLLER\n";
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n-----------------------------------------------------------------\n";

// 5. Verify view files exist
$viewFiles = [
    'admin.scholars.index' => resource_path('views/admin/scholars/index.blade.php'),
    'admin.scholars.create' => resource_path('views/admin/scholars/create.blade.php'),
    'admin.scholars.edit' => resource_path('views/admin/scholars/edit.blade.php'),
    'admin.scholars.show' => resource_path('views/admin/scholars/show.blade.php'),
];

echo "✅ VIEW FILES\n";
foreach ($viewFiles as $view => $path) {
    echo "   $view: " . (file_exists($path) ? "Exists ✓" : "Missing ✗") . "\n";
}

echo "\n=================================================================\n";
echo "                  TEST COMPLETED                     \n";
echo "=================================================================\n\n";
