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
        // Check if fund_requests table exists before trying to update it
        if (!Schema::hasTable('fund_requests')) {
            return;
        }

        // First, update any existing 'Disbursed' status to 'Completed'
        DB::table('fund_requests')
            ->where('status', 'Disbursed')
            ->update(['status' => 'Completed']);

        // Update the enum to remove 'Disbursed' and ensure all valid statuses are present
        DB::statement("ALTER TABLE fund_requests MODIFY COLUMN status ENUM('Draft', 'Submitted', 'Under Review', 'Approved', 'Rejected', 'Completed') NOT NULL DEFAULT 'Draft'");

        // Update status_history for any records that had 'Disbursed' status
        $fundRequests = DB::table('fund_requests')
            ->whereNotNull('status_history')
            ->get();

        foreach ($fundRequests as $request) {
            $statusHistory = json_decode($request->status_history, true);
            if (is_array($statusHistory)) {
                $updated = false;
                foreach ($statusHistory as &$entry) {
                    if (isset($entry['status']) && $entry['status'] === 'Disbursed') {
                        $entry['status'] = 'Completed';
                        $updated = true;
                    }
                    if (isset($entry['old_status']) && $entry['old_status'] === 'Disbursed') {
                        $entry['old_status'] = 'Completed';
                        $updated = true;
                    }
                    if (isset($entry['new_status']) && $entry['new_status'] === 'Disbursed') {
                        $entry['new_status'] = 'Completed';
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'Completed' back to 'Disbursed'
        DB::table('fund_requests')
            ->where('status', 'Completed')
            ->update(['status' => 'Disbursed']);

        // Restore the original enum
        DB::statement("ALTER TABLE fund_requests MODIFY COLUMN status ENUM('Draft', 'Submitted', 'Under Review', 'Approved', 'Rejected', 'Disbursed') NOT NULL DEFAULT 'Draft'");

        // Revert status_history changes
        $fundRequests = DB::table('fund_requests')
            ->whereNotNull('status_history')
            ->get();

        foreach ($fundRequests as $request) {
            $statusHistory = json_decode($request->status_history, true);
            if (is_array($statusHistory)) {
                $updated = false;
                foreach ($statusHistory as &$entry) {
                    if (isset($entry['status']) && $entry['status'] === 'Completed') {
                        $entry['status'] = 'Disbursed';
                        $updated = true;
                    }
                    if (isset($entry['old_status']) && $entry['old_status'] === 'Completed') {
                        $entry['old_status'] = 'Disbursed';
                        $updated = true;
                    }
                    if (isset($entry['new_status']) && $entry['new_status'] === 'Completed') {
                        $entry['new_status'] = 'Disbursed';
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
};