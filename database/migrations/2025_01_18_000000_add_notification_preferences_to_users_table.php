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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email_notifications')) {
                $table->boolean('email_notifications')->default(true);
            }
            if (!Schema::hasColumn('users', 'fund_request_notifications')) {
                $table->boolean('fund_request_notifications')->default(true);
            }
            if (!Schema::hasColumn('users', 'document_notifications')) {
                $table->boolean('document_notifications')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'email_notifications')) {
                $table->dropColumn('email_notifications');
            }
            if (Schema::hasColumn('users', 'fund_request_notifications')) {
                $table->dropColumn('fund_request_notifications');
            }
            if (Schema::hasColumn('users', 'document_notifications')) {
                $table->dropColumn('document_notifications');
            }
        });
    }
};
