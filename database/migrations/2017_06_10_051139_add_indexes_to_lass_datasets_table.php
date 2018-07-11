<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToLassDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lass_datasets', function (Blueprint $table) {
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
        Schema::table('lass_datasets', function (Blueprint $table) {
            $table->dropIndex(['published_datetime']);
            $table->dropUnique(['site_id', 'published_datetime']);
        });
    }
}
