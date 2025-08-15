<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if manuscripts table exists before trying to modify it
        if (!Schema::hasTable('manuscripts')) {
            return;
        }

        Schema::table('manuscripts', function (Blueprint $table) {
            $table->foreignId('fund_request_id')->nullable()->after('scholar_profile_id')->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('manuscripts', function (Blueprint $table) {
            $table->dropForeign(['fund_request_id']);
            $table->dropColumn('fund_request_id');
        });
    }
};