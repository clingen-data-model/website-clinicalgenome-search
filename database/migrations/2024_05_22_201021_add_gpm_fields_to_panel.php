<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGpmFieldsToPanel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panels', function (Blueprint $table) {
            //
            $table->string('wg_status')->nullable();
            $table->text('metadata_search_terms')->nullable();
            $table->integer('is_active')->nullable();
            $table->dateTime('inactive_date')->nullable();
            $table->string('group_clinvar_org_id')->nullable();
            $table->string('url_clinvar')->nullable();
            $table->string('url_cspec')->nullable();
            $table->string('url_curations')->nullable();
            $table->string('url_erepo')->nullable();
            $table->text('description')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('panels', function (Blueprint $table) {
            //
        });
    }
}
