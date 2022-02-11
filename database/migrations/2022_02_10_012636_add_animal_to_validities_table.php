<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnimalToValiditiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('validities', function (Blueprint $table) {
            $table->string('report_id')->nullable()->after('report_date');
            $table->boolean('animal_model_only')->default(false)->after('attributed_to');
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
