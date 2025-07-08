<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('scholar_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('scholar_profiles', 'major')) {
                $table->string('major')->nullable()->after('program');
            }
        });
    }

    public function down()
    {
        Schema::table('scholar_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('scholar_profiles', 'major')) {
                $table->dropColumn('major');
            }
        });
    }
};
