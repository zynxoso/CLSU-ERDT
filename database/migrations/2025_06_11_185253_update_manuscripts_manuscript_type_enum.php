<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateManuscriptsManuscriptTypeEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
