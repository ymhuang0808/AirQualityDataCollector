<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpaDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epa_datasets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('so2', 4, 1);
            $table->double('co', 5, 2);
            $table->integer('o3');
            $table->integer('pm10');
            $table->integer('pm25');
            $table->integer('no2');
            $table->double('wind_speed', 5, 1);
            $table->integer('wind_direction');
            $table->integer('fpmi');
            $table->double('nox', 5, 1);
            $table->double('no', 5, 1);
            $table->unsignedInteger('site_id');
            $table->dateTimeTz('published_datetime');
            $table->dateTimeTz('updated_at');
            $table->dateTimeTz('created_at');

            $table->foreign('site_id')->references('id')->on('sites')
              ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('epa_datasets');
    }
}
