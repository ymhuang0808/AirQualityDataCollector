<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFieldsInEpaDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('epa_datasets', function (Blueprint $table) {
            $table->dropColumn(['psi', 'fpmi']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('epa_datasets', function (Blueprint $table) {
            if (!Schema::hasColumn('epa_datasets', 'psi')) {
                $table->integer('psi')->nullable();
            }

            if (!Schema::hasColumn('epa_datasets', 'fpmi')) {
                $table->integer('fpmi')->nullable();
            }
        });
    }
}
