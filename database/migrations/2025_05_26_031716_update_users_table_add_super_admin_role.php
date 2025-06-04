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
        // We need to use DB::statement for this as it's a direct SQL operation
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'scholar', 'super_admin') DEFAULT 'scholar'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original enum values
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'scholar') DEFAULT 'scholar'");
    }
};
