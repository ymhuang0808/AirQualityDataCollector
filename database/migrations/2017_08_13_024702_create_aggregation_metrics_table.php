<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAggregationMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aggregation_metrics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTimeTz('start_datetime');
            $table->dateTimeTz('end_datetime');
            $table->unsignedBigInteger('site_id');
            $table->binary('values');
            $table->tinyInteger('period_type');
            $table->dateTimeTz('updated_at');
            $table->dateTimeTz('created_at');

            // Build an relationship with sites table by site_id
            $table->foreign('site_id')->references('id')->on('sites')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['start_datetime', 'end_datetime', 'site_id', 'period_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aggregation_metrics');
    }
}
