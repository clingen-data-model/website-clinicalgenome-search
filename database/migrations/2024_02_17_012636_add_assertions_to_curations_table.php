<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssertionsToCurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curations', function (Blueprint $table) {
            $table->string('curation_version')->after('sop_version')->nullable();
            $table->foreignId('panel_id')->after('alternate_uuid')->nullable();
            $table->integer('source_timestamp')->after('source_uuid')->default(0);
            $table->integer('source_offset')->after('source_timestamp')->default(0);
            $table->string('message_version')->after('source_offset')->nullable();
           // $table->string('assertion_uuid')->after('message_version')->nullable();
            $table->json('assertions')->after('evidence_details')->nullable();
            $table->json('url')->after('events')->nullable();

;        });
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
