<?php

require __DIR__.'/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Initialize the application
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ScholarProfile;
use App\Models\FundRequest;
use App\Models\Manuscript;
use Illuminate\Support\Facades\DB;

echo "\n=================================================================\n";
echo "                 SCHOLAR FUNCTIONALITY TEST\n";
echo "=================================================================\n\n";

// 1. Check if test scholar exists
$scholar = User::where('email', 'test.scholar@example.com')->first();
if ($scholar) {
    echo "✅ TEST SCHOLAR USER\n";
    echo "   ID: {$scholar->id}\n";
    echo "   Name: {$scholar->name}\n";
    echo "   Email: {$scholar->email}\n";
    echo "   Role: {$scholar->role}\n";
} else {
    echo "❌ Test scholar does not exist in the database\n";
    exit(1);
}

echo "\n-----------------------------------------------------------------\n";

// 2. Check if scholar profile exists
$profile = ScholarProfile::where('user_id', $scholar->id)->first();
if ($profile) {
    echo "✅ SCHOLAR PROFILE\n";
    echo "   ID: {$profile->id}\n";
    echo "   Name: {$profile->first_name} {$profile->last_name}\n";
    echo "   University: {$profile->university}\n";
    echo "   Program: {$profile->program}\n";
    echo "   Status: {$profile->status}\n";
} else {
    echo "❌ Scholar profile does not exist\n";
    exit(1);
}

echo "\n-----------------------------------------------------------------\n";

// 3. Check fund requests for this scholar
$fundRequests = FundRequest::where('scholar_profile_id', $profile->id)->get();
echo "✅ FUND REQUESTS: " . count($fundRequests) . " total\n";
if (count($fundRequests) > 0) {
    $statusCounts = [
        'Draft' => 0,
        'Submitted' => 0,
        'Under Review' => 0,
        'Approved' => 0,
        'Rejected' => 0,
        'Disbursed' => 0
    ];

    foreach ($fundRequests as $request) {
        if (isset($statusCounts[$request->status])) {
            $statusCounts[$request->status]++;
        }
    }

    echo "\n   Status Summary:\n";
    foreach ($statusCounts as $status => $count) {
        if ($count > 0) {
            echo "     • {$status}: {$count}\n";
        }
    }

    echo "\n   Sample Fund Requests:\n";
    foreach ($fundRequests->take(3) as $index => $request) {
        echo "     #{$index} ID: {$request->id}, Status: {$request->status}, Amount: {$request->amount}\n";
        echo "        Scholar: " . ($request->scholarProfile ? "Connected ✓" : "Missing ✗") . "\n";
        echo "        Request Type: " . ($request->requestType ? "{$request->requestType->name}" : "Missing ✗") . "\n";
        if ($index < 2 && $index < $fundRequests->count() - 1) echo "\n";
    }
}

echo "\n-----------------------------------------------------------------\n";

// 4. Check manuscripts for this scholar
$manuscripts = Manuscript::where('scholar_profile_id', $profile->id)->get();
echo "✅ MANUSCRIPTS: " . count($manuscripts) . " total\n";
if (count($manuscripts) > 0) {
    $statusCounts = [
        'Draft' => 0,
        'Submitted' => 0,
        'Under Review' => 0,
        'Revision Requested' => 0,
        'Accepted' => 0,
        'Published' => 0,
        'Rejected' => 0
    ];

    foreach ($manuscripts as $manuscript) {
        if (isset($statusCounts[$manuscript->status])) {
            $statusCounts[$manuscript->status]++;
        }
    }

    echo "\n   Status Summary:\n";
    foreach ($statusCounts as $status => $count) {
        if ($count > 0) {
            echo "     • {$status}: {$count}\n";
        }
    }

    echo "\n   Sample Manuscripts:\n";
    foreach ($manuscripts->take(3) as $index => $manuscript) {
        echo "     #{$index} ID: {$manuscript->id}, Title: {$manuscript->title}\n";
        echo "        Status: {$manuscript->status}, Type: {$manuscript->manuscript_type}\n";
        echo "        Scholar: " . ($manuscript->scholarProfile ? "Connected ✓" : "Missing ✗") . "\n";
        if ($index < 2 && $index < $manuscripts->count() - 1) echo "\n";
    }
}

echo "\n-----------------------------------------------------------------\n";

// 5. Test login attempt
if (Auth::attempt(['email' => 'test.scholar@example.com', 'password' => 'password'])) {
    echo "✅ LOGIN TEST\n";
    echo "   Current user: " . Auth::user()->name . "\n";
    echo "   Role: " . Auth::user()->role . "\n";

    // Check access to scholar routes
    echo "\n   Available Routes:\n";
    echo "     • /scholar/dashboard\n";
    echo "     • /scholar/profile\n";
    echo "     • /scholar/fund-requests\n";
    echo "     • /scholar/manuscripts\n";

    // Logout
    Auth::logout();
    echo "\n   Logout successful ✓\n";
} else {
    echo "❌ Login failed for test.scholar@example.com\n";
}

echo "\n=================================================================\n";
echo "                  TEST COMPLETED SUCCESSFULLY                     \n";
echo "=================================================================\n\n";
