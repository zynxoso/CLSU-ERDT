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
        Schema::table('audit_logs', function (Blueprint $table) {
            // First, copy any data from entity_type/entity_id to model_type/model_id if needed
            DB::statement('UPDATE audit_logs SET model_type = entity_type WHERE entity_type IS NOT NULL AND (model_type IS NULL OR model_type = "")');
            DB::statement('UPDATE audit_logs SET model_id = entity_id WHERE entity_id IS NOT NULL AND model_id IS NULL');

            // Make model_type nullable temporarily to avoid constraint issues
            $table->string('model_type')->nullable()->change();
        });

        // Drop the duplicate columns
        Schema::table('audit_logs', function (Blueprint $table) {
            if (Schema::hasColumn('audit_logs', 'entity_type')) {
                $table->dropColumn('entity_type');
            }
            if (Schema::hasColumn('audit_logs', 'entity_id')) {
                $table->dropColumn('entity_id');
            }
        });

        // Set default values for any null model_type fields
        DB::statement('UPDATE audit_logs SET model_type = "Unknown" WHERE model_type IS NULL OR model_type = ""');

        // Make model_type required again
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('model_type')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Add back the entity columns
            $table->string('entity_type')->nullable()->after('model_id');
            $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
        });

        // Copy data back
        DB::statement('UPDATE audit_logs SET entity_type = model_type WHERE model_type IS NOT NULL');
        DB::statement('UPDATE audit_logs SET entity_id = model_id WHERE model_id IS NOT NULL');
    }
};
