<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMajorPollutantToPollutantInEpaDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('epa_datasets', function (Blueprint $table) {
            $table->renameColumn('major_pollutant', 'pollutant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('epa_datasets', function (Blueprint $table) {
            $table->renameColumn('pollutant', 'major_pollutant');
        });
    }
}
