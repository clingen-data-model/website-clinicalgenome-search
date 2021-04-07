<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNondiseaseToMorbidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('morbids', function (Blueprint $table) {
            $table->string('nondisease')->nullable()->after('disputing');
            $table->string('mutations')->nullable()->after('nondisease');
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
