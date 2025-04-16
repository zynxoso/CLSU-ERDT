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
        // MySQL requires dropping and recreating the column to change ENUM values
        if (DB::getDriverName() === 'mysql') {
            // First get all the existing data
            $manuscripts = DB::table('manuscripts')->select(['id', 'status'])->get();

            // Then alter the table
            Schema::table('manuscripts', function (Blueprint $table) {
                // Drop the existing column
                $table->dropColumn('status');
            });

            Schema::table('manuscripts', function (Blueprint $table) {
                // Create the new column with updated ENUM values
                $table->enum('status', ['Draft', 'Submitted', 'Under Review', 'Revision Requested', 'Accepted', 'Published', 'Rejected'])
                    ->default('Draft')
                    ->after('keywords');
            });

            // Map old values to new values if needed
            foreach ($manuscripts as $manuscript) {
                $newStatus = $this->mapOldStatusToNew($manuscript->status);
                DB::table('manuscripts')
                    ->where('id', $manuscript->id)
                    ->update(['status' => $newStatus]);
            }
        }
    }

    /**
     * Map old status values to new ones
     */
    private function mapOldStatusToNew($oldStatus)
    {
        $map = [
            'Outline Submitted' => 'Submitted',
            'Outline Approved' => 'Accepted',
            'Draft Submitted' => 'Submitted',
            // Keep the existing values for these
            'Under Review' => 'Under Review',
            'Revision Requested' => 'Revision Requested',
            'Accepted' => 'Accepted',
            'Published' => 'Published',
            // Add any mapping for other old values
        ];

        return $map[$oldStatus] ?? 'Draft'; // Default to Draft if no mapping exists
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // MySQL requires dropping and recreating the column to change ENUM values
        if (DB::getDriverName() === 'mysql') {
            // First get all the existing data
            $manuscripts = DB::table('manuscripts')->select(['id', 'status'])->get();

            // Then alter the table
            Schema::table('manuscripts', function (Blueprint $table) {
                // Drop the new column
                $table->dropColumn('status');
            });

            Schema::table('manuscripts', function (Blueprint $table) {
                // Create the old column with original ENUM values
                $table->enum('status', ['Outline Submitted', 'Outline Approved', 'Draft Submitted', 'Under Review', 'Revision Requested', 'Accepted', 'Published'])
                    ->default('Outline Submitted')
                    ->after('keywords');
            });

            // Map new values back to old values
            foreach ($manuscripts as $manuscript) {
                $oldStatus = $this->mapNewStatusToOld($manuscript->status);
                DB::table('manuscripts')
                    ->where('id', $manuscript->id)
                    ->update(['status' => $oldStatus]);
            }
        }
    }

    /**
     * Map new status values back to old ones
     */
    private function mapNewStatusToOld($newStatus)
    {
        $map = [
            'Submitted' => 'Outline Submitted',
            'Accepted' => 'Outline Approved',
            // Keep the existing values for these
            'Under Review' => 'Under Review',
            'Revision Requested' => 'Revision Requested',
            'Published' => 'Published',
            'Rejected' => 'Under Review', // Map rejected to Under Review since rejected didn't exist before
        ];

        return $map[$newStatus] ?? 'Outline Submitted'; // Default if no mapping exists
    }
};
