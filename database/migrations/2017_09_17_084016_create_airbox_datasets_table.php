<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirboxDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airbox_datasets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('pm25', 5, 1)->nullable();
            $table->double('pm10', 5, 1)->nullable();
            $table->double('temperature', 5, 2)->nullable();
            $table->double('humidity', 5, 2)->nullable();
            $table->unsignedInteger('site_id');
            $table->dateTimeTz('published_datetime');
            $table->dateTimeTz('updated_at');
            $table->dateTimeTz('created_at');

            $table->foreign('site_id')->references('id')->on('sites')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->index(['published_datetime']);
            $table->unique(['site_id', 'published_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airbox_datasets');
    }
}
