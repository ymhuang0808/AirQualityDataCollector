<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHumanizedNameToSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // A workaround for SQLite database. The migrations should be separated into each closure
        // Related issues: https://github.com/laravel/framework/issues/2979

        Schema::table('sites', function (Blueprint $table) {
            $table->string('humanized_name')->nullable()->after('name');
        });

        Schema::table('sites', function (Blueprint $table) {
            $table->renameColumn('eng_name', 'humanized_eng_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // A workaround for SQLite database. The migrations should be separated into each closure
        // Related issues: https://github.com/laravel/framework/issues/2979

        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('humanized_name');
        });

        Schema::table('sites', function (Blueprint $table) {
            $table->renameColumn('humanized_eng_name', 'eng_name');
        });
    }
}
