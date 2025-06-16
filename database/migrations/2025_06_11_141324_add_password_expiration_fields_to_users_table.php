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
            $table->timestamp('password_expires_at')->nullable()->after('password');
            $table->timestamp('password_changed_at')->nullable()->after('password_expires_at');
            $table->boolean('must_change_password')->default(false)->after('password_changed_at');
            $table->boolean('is_default_password')->default(true)->after('must_change_password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'password_expires_at',
                'password_changed_at',
                'must_change_password',
                'is_default_password'
            ]);
        });
    }
};
