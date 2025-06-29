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
        Schema::table('history_achievements', function (Blueprint $table) {
            $table->text('icon')->nullable()->change(); // Change from string to text
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_achievements', function (Blueprint $table) {
            $table->string('icon')->nullable()->change(); // Revert back to string
        });
    }
};
