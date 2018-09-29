<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCacheTableValueToLongtextType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cache', function (Blueprint $table) {
            $table->dropColumn('value');
        });

        Schema::table('cache', function (Blueprint $table) {
            $table->longText('value')->nullable()->after('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cache', function (Blueprint $table) {
            $table->dropColumn('value');
        });

        Schema::table('cache', function (Blueprint $table) {
            $table->text('value')->nullable()->after('key');
        });
    }
}
