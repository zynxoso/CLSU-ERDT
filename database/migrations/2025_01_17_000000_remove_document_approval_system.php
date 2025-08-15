<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['verified_by']);
            
            // Remove approval-related columns that actually exist
            $table->dropColumn([
                'is_verified',
                'verified_by',
                'verified_at'
            ]);
            
            // Status column already exists with default 'active'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            // Add back the approval-related columns that were removed
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
        });
    }
};