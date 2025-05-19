<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the schema builder
$schema = DB::getSchemaBuilder();

// Check if the table exists
if ($schema->hasTable('scholar_profiles')) {
    echo "Table scholar_profiles exists\n";
    
    // Get the column information
    $columns = $schema->getColumnListing('scholar_profiles');
    echo "Columns in scholar_profiles table:\n";
    foreach ($columns as $column) {
        echo "- $column\n";
    }
    
    // Get the status column type (for MySQL)
    $type = DB::select("SHOW COLUMNS FROM scholar_profiles WHERE Field = 'status'");
    echo "\nStatus column details:\n";
    print_r($type);
} else {
    echo "Table scholar_profiles does not exist\n";
}
