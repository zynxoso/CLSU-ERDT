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
        // First, check if the table exists
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('action');
                $table->string('model_type')->nullable();
                $table->unsignedBigInteger('model_id')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['model_type', 'model_id']);
                $table->index('user_id');
                $table->index('created_at');
            });
            return;
        }

        // Clean up the schema if the table exists
        Schema::table('audit_logs', function (Blueprint $table) {
            // Drop entity_type column if it exists
            if (Schema::hasColumn('audit_logs', 'entity_type')) {
                $table->dropColumn('entity_type');
            }

            // Drop entity_id column if it exists
            if (Schema::hasColumn('audit_logs', 'entity_id')) {
                $table->dropColumn('entity_id');
            }
        });

        // Ensure model_type allows NULL values to prevent the error
        DB::statement('ALTER TABLE audit_logs MODIFY model_type VARCHAR(255) NULL');

        // Ensure model_id allows NULL values
        DB::statement('ALTER TABLE audit_logs MODIFY model_id BIGINT UNSIGNED NULL');

        // Add indexes if they don't exist
        try {
            DB::statement('CREATE INDEX IF NOT EXISTS idx_audit_logs_model ON audit_logs(model_type, model_id)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_audit_logs_user ON audit_logs(user_id)');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_audit_logs_created ON audit_logs(created_at)');
        } catch (Exception $e) {
            // Indexes might already exist, ignore errors
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is a cleanup, so we don't reverse it
        // to avoid breaking the system again
    }
};
