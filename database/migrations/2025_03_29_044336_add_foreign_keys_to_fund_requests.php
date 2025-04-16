<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fund_requests', function (Blueprint $table) {
            $table->foreign('request_type_id')->references('id')->on('request_types');
        });
    }

    public function down()
    {
        Schema::table('fund_requests', function (Blueprint $table) {
            $table->dropForeign(['request_type_id']);
        });
    }
};