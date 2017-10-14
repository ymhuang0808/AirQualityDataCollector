<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNo2DoubleTypeColumnInEpaDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('epa_datasets', function (Blueprint $table) {
            $table->double('no2', 4, 1)->nullable();
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
            $table->dropColumn('no2');
        });
    }
}
