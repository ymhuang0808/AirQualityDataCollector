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
            $table->integer('psi')->nullable();
            $table->double('so2', 4, 1)->nullable();
            $table->double('co', 5, 2)->nullable();
            $table->integer('o3')->nullable();
            $table->integer('pm10')->nullable();
            $table->integer('pm25')->nullable();
            $table->integer('no2')->nullable();
            $table->double('wind_speed', 5, 1)->nullable();
            $table->integer('wind_direction')->nullable();
            $table->integer('fpmi')->nullable();
            $table->double('nox', 5, 1)->nullable();
            $table->double('no', 5, 1)->nullable();
            $table->string('major_pollutant')->nullable();
            $table->string('status')->nullable();
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
