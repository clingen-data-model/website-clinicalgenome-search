<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhenomimToMorbidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('morbids', function (Blueprint $table) {
            $table->string('secondary')->nullable()->after('phenotype');
            $table->string('pheno_omim')->nullable()->after('secondary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('morbids', function (Blueprint $table) {
            //
        });
    }
}
