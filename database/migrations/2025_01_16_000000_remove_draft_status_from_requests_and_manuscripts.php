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
        // Update fund_requests table
        if (Schema::hasTable('fund_requests')) {
            // First, update any existing 'Draft' status to 'Submitted'
            DB::table('fund_requests')
                ->where('status', 'Draft')
                ->update(['status' => 'Submitted']);

            // Update the enum to remove 'Draft'
            DB::statement("ALTER TABLE fund_requests MODIFY COLUMN status ENUM('Submitted', 'Under Review', 'Approved', 'Rejected', 'Completed') NOT NULL DEFAULT 'Submitted'");

            // Update status_history for any records that had 'Draft' status
            $fundRequests = DB::table('fund_requests')
                ->whereNotNull('status_history')
                ->get();

            foreach ($fundRequests as $request) {
                $statusHistory = json_decode($request->status_history, true);
                if (is_array($statusHistory)) {
                    $updated = false;
                    foreach ($statusHistory as &$entry) {
                        if (isset($entry['status']) && $entry['status'] === 'Draft') {
                            $entry['status'] = 'Submitted';
                            $updated = true;
                        }
                        if (isset($entry['old_status']) && $entry['old_status'] === 'Draft') {
                            $entry['old_status'] = 'Submitted';
                            $updated = true;
                        }
                        if (isset($entry['new_status']) && $entry['new_status'] === 'Draft') {
                            $entry['new_status'] = 'Submitted';
                            $updated = true;
                        }
                        if (isset($entry['previous_status']) && $entry['previous_status'] === 'Draft') {
                            $entry['previous_status'] = 'Submitted';
                            $updated = true;
                        }
                    }
                    
                    if ($updated) {
                        DB::table('fund_requests')
                            ->where('id', $request->id)
                            ->update(['status_history' => json_encode($statusHistory)]);
                    }
                }
            }
        }

        // Update manuscripts table
        if (Schema::hasTable('manuscripts')) {
            // First, update any existing 'Draft' status to 'Submitted'
            DB::table('manuscripts')
                ->where('status', 'Draft')
                ->update(['status' => 'Submitted']);

            // Update the enum to remove 'Draft'
            DB::statement("ALTER TABLE manuscripts MODIFY COLUMN status ENUM('Submitted', 'Under Review', 'Revision Requested', 'Accepted', 'Published', 'Rejected') NOT NULL DEFAULT 'Submitted'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore fund_requests table
        if (Schema::hasTable('fund_requests')) {
            // Restore the original enum with 'Draft'
            DB::statement("ALTER TABLE fund_requests MODIFY COLUMN status ENUM('Draft', 'Submitted', 'Under Review', 'Approved', 'Rejected', 'Completed') NOT NULL DEFAULT 'Draft'");

            // Revert 'Submitted' back to 'Draft' for records that were originally Draft
            // Note: This is a simplified revert - in practice, you might want to track which records were originally Draft
        }

        // Restore manuscripts table
        if (Schema::hasTable('manuscripts')) {
            // Restore the original enum with 'Draft'
            DB::statement("ALTER TABLE manuscripts MODIFY COLUMN status ENUM('Draft', 'Submitted', 'Under Review', 'Revision Requested', 'Accepted', 'Published', 'Rejected') NOT NULL DEFAULT 'Draft'");
        }
    }
};