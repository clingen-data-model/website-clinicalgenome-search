<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasToCpicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cpics', function (Blueprint $table) {
            $table->string('hgnc_id')->nullable()->after('gene');
            $table->string('pa_id')->nullable()->after('pharmgkb_level_of_evidence');
            $table->string('is_vip')->nullable()->after('pa_id');
            $table->string('has_va')->nullable()->after('is_vip');
            $table->string('had_cpic_guideline')->nullable()->after('has_va');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cpics', function (Blueprint $table) {
            //
        });
    }
}
