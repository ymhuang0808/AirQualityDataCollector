<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAggregationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aggregation_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('aggregation_type');
            $table->string('source_type');
            $table->dateTimeTz('start_datetime')->nullable();
            $table->dateTimeTz('end_datetime')->nullable();
            $table->longText('message')->nullable();
            $table->smallInteger('level');
            $table->dateTimeTz('updated_at');
            $table->dateTimeTz('created_at');

            $table->index('aggregation_type');
            $table->index('source_type');
            $table->index(['aggregation_type', 'source_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aggregation_logs');
    }
}
