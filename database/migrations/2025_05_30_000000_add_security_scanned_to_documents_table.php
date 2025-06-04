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
            $table->boolean('security_scanned')->default(false)->after('status');
            $table->timestamp('security_scanned_at')->nullable()->after('security_scanned');
            $table->string('security_scan_result')->nullable()->after('security_scanned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('security_scanned');
            $table->dropColumn('security_scanned_at');
            $table->dropColumn('security_scan_result');
        });
    }
};
