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
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'description')) {
                $table->text('description')->nullable()->after('category');
            }

            if (!Schema::hasColumn('documents', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('verified_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('documents', 'admin_notes')) {
                $table->dropColumn('admin_notes');
            }
        });
    }
};
