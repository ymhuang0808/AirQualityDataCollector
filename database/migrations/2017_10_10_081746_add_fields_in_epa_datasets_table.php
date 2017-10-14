<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInEpaDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('epa_datasets', function (Blueprint $table) {
            $table->integer('aqi')->nullable();
            $table->double('co_8hr', 5, 2)->nullable();
            $table->integer('o3_8hr')->nullable();
            $table->integer('pm10_avg')->nullable();
            $table->integer('pm25_avg')->nullable();
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
            $table->dropColumn(['aqi', 'co_8hr', 'o3_8hr', 'pm10_avg', 'pm25_avg']);
        });
    }
}
