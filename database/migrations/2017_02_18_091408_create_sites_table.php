<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('eng_name')->nullable();
            $table->string('address')->nullable();
            $table->string('type')->nullable();
            $table->string('area_name')->nullable();
            $table->unsignedInteger('county_id')->nullable();
            $table->unsignedInteger('township_id')->nullable();
            $table->binary('coordinates');
            $table->string('source_type');
            $table->dateTimeTz('updated_at');
            $table->dateTimeTz('created_at');

            $table->unique(['name', 'source_type']);

            $table->foreign('county_id')->references('id')->on('counties')
              ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('township_id')->references('id')->on('townships')
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
        Schema::dropIfExists('sites');
    }
}
