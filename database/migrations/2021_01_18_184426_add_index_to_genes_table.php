<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToGenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('genes', function (Blueprint $table) {
            $table->index('acmg59');
            $table->boolean('has_pharma')->virtualAs('activity->"$.pharma"')->after('activity');
            $table->index('has_pharma');
            $table->boolean('has_varpath')->virtualAs('activity->"$.varpath"')->after('activity');
            $table->index('has_varpath');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dosages', function (Blueprint $table) {
            //
        });
    }
}
