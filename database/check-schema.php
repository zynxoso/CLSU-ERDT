<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the schema builder
$schema = DB::getSchemaBuilder();

// Define essential tables to check
$essentialTables = [
    'users',
    'scholar_profiles',
    'fund_requests',
    'manuscripts',
    'documents',
    'request_types',
    'site_settings',
    'audit_logs',
    'notifications',
    'application_timeline',
    'important_notes',
    'announcements'
];

// Check each table
foreach ($essentialTables as $table) {
    echo "\nChecking table: $table\n";
    echo "------------------------\n";
    
    if ($schema->hasTable($table)) {
        echo "✓ Table exists\n";
        
        // Get and display columns
        $columns = $schema->getColumnListing($table);
        echo "\nColumns:\n";
        foreach ($columns as $column) {
            // Get column type and other details
            $type = DB::select("SHOW COLUMNS FROM $table WHERE Field = ?", [$column])[0];
            echo sprintf("- %-20s | Type: %-20s | Null: %-5s | Key: %-5s\n",
                $column,
                $type->Type,
                $type->Null,
                $type->Key
            );
        }

        // Check for indexes
        $indexes = DB::select("SHOW INDEXES FROM $table");
        if (!empty($indexes)) {
            echo "\nIndexes:\n";
            foreach ($indexes as $index) {
                echo sprintf("- %-20s | Column: %-20s | Type: %s\n",
                    $index->Key_name,
                    $index->Column_name,
                    $index->Index_type
                );
            }
        }
    } else {
        echo "✗ Table does not exist!\n";
    }
    
    echo "\n";
}

// Check foreign key relationships
echo "\nChecking Foreign Key Relationships:\n";
echo "--------------------------------\n";

$relationships = [
    'scholar_profiles' => ['users' => 'user_id'],
    'fund_requests' => [
        'users' => 'user_id',
        'request_types' => 'request_type_id'
    ],
    'manuscripts' => ['users' => 'user_id'],
    'documents' => ['users' => 'user_id'],
    'notifications' => ['users' => 'user_id']
];

foreach ($relationships as $table => $relations) {
    if ($schema->hasTable($table)) {
        foreach ($relations as $relatedTable => $foreignKey) {
            $constraint = DB::select("
                SELECT *
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = ?
                AND COLUMN_NAME = ?
                AND REFERENCED_TABLE_NAME = ?",
                [$table, $foreignKey, $relatedTable]
            );
            
            if (!empty($constraint)) {
                echo "✓ Foreign key exists: $table.$foreignKey -> $relatedTable\n";
            } else {
                echo "✗ Missing foreign key: $table.$foreignKey -> $relatedTable\n";
            }
        }
    }
}

// Output summary
echo "\nSchema Check Summary:\n";
echo "-------------------\n";
echo "Total tables checked: " . count($essentialTables) . "\n";
echo "Total relationships checked: " . array_sum(array_map('count', $relationships)) . "\n";
