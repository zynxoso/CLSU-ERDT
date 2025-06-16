<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateManuscriptsManuscriptTypeDataAndEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update existing manuscript_type values to 'Outline' if they are not 'Outline' or 'Final'
        DB::table('manuscripts')
            ->whereNotIn('manuscript_type', ['Outline', 'Final'])
            ->update(['manuscript_type' => 'Outline']);

        // Modify the manuscript_type enum to include Outline and Final
        Schema::table('manuscripts', function (Blueprint $table) {
            $table->enum('manuscript_type', ['Outline', 'Final'])->default('Outline')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the manuscript_type enum to the original values
        Schema::table('manuscripts', function (Blueprint $table) {
            $table->enum('manuscript_type', ['Conference Paper', 'Journal Article', 'Thesis', 'Dissertation', 'Book Chapter', 'Other'])->default('Journal Article')->change();
        });
    }
}
