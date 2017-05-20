<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('level');
            $table->string('channel', 32);
            $table->string('source_type', 32);
            $table->integer('count');
            $table->string('message');
            $table->dateTimeTz('updated_at');
            $table->dateTimeTz('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_logs');
    }
}
