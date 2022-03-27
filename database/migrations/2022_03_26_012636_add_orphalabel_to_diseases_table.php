<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrphalabelToDiseasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diseases', function (Blueprint $table) {
            $table->string('orpha_label')->nullable()->after('orpha_id');
            $table->string('omim_label')->nullable()->after('omim');
            $table->index('orpha_label');
            $table->index('omim_label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::table('streams', function (Blueprint $table) {
            //
        //});
    }
}
