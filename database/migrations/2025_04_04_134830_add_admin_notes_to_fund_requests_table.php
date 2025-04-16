<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This is unnecessary as the fund_requests table already has:
        // - admin_remarks (similar to admin_notes)
        // - reviewed_by column
        // - reviewed_at column

        // Instead, we'll do nothing as the existing columns serve the same purpose
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No changes to revert
    }
};
