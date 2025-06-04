<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$superAdmin = App\Models\User::where('role', 'super_admin')->first();

if ($superAdmin) {
    echo "Super Admin user found:\n";
    echo "Name: " . $superAdmin->name . "\n";
    echo "Email: " . $superAdmin->email . "\n";
    echo "Role: " . $superAdmin->role . "\n";
    echo "Created at: " . $superAdmin->created_at . "\n";
} else {
    echo "No Super Admin user found in the database.\n";
}
