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
        // Standardize all status values to use consistent capitalization
        DB::table('manuscripts')
            ->where('status', 'draft')
            ->update(['status' => 'Draft']);

        DB::table('manuscripts')
            ->where('status', 'submitted')
            ->update(['status' => 'Submitted']);

        DB::table('manuscripts')
            ->where('status', 'under_review')
            ->update(['status' => 'Under Review']);

        DB::table('manuscripts')
            ->where('status', 'revision_required')
            ->update(['status' => 'Revision Requested']);

        DB::table('manuscripts')
            ->where('status', 'accepted')
            ->update(['status' => 'Accepted']);

        DB::table('manuscripts')
            ->where('status', 'published')
            ->update(['status' => 'Published']);

        DB::table('manuscripts')
            ->where('status', 'rejected')
            ->update(['status' => 'Rejected']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration as it's a data standardization
    }
};
