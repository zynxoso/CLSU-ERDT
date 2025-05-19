<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any records that might have 'Deferred Repayment' status
        // to prevent data loss (change them to 'Graduated' as a fallback)
        DB::table('scholar_profiles')
            ->where('status', 'Deferred Repayment')
            ->update(['status' => 'Graduated']);

        // Use raw SQL to update the enum values
        // This approach avoids the foreign key constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Update the enum values
        DB::statement("ALTER TABLE scholar_profiles MODIFY COLUMN status ENUM('New', 'Ongoing', 'On Extension', 'Graduated', 'Terminated') DEFAULT 'New'");
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Restore the original enum values including 'Deferred Repayment'
        DB::statement("ALTER TABLE scholar_profiles MODIFY COLUMN status ENUM('New', 'Ongoing', 'On Extension', 'Graduated', 'Terminated', 'Deferred Repayment') DEFAULT 'New'");
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
