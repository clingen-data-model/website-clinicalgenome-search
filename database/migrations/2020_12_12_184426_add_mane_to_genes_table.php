<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManeToGenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('genes', function (Blueprint $table) {
            $table->json('mane_select')->nullable()->after('stop38');
            $table->json('mane_plus')->nullable()->after('mane_select');
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
