<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoordsToRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->jsonb('coordinates')->after('location')->nullable();
            $table->string('type_string')->after('type')->nullable();
            $table->tinyInteger('subtype')->after('type_string')->default(0);
            $table->string('subtype_string')->after('subtype')->nullable();
            $table->jsonb('scores')->after('loss')->nullable();
            $table->string('cytoband')->after('location')->nullable();
            $table->text('description')->after('subtype_string')->nullable();
            $table->string('iri')->after('subtype_string')->nullable();
            $table->json('tags')->after('description')->nullable();
            $table->json('metadata')->after('scores')->nullable();
            $table->json('events')->after('scores')->nullable();
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
