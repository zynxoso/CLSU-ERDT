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
        Schema::table('manuscripts', function (Blueprint $table) {
            $table->foreignId('fund_request_id')->nullable()->after('scholar_profile_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manuscripts', function (Blueprint $table) {
            $table->dropForeign(['fund_request_id']);
            $table->dropColumn('fund_request_id');
        });
    }
};
