<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariantsToCurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curations', function (Blueprint $table) {
            $table->string('variant_iri')->after('gene_details')->nullable();
            $table->json('variant_details')->after('variant_iri')->nullable();
            $table->foreignId('gene_id')->after('affiliate_details')->nullable();
            $table->foreignId('disease_id')->after('comments')->nullable();
            $table->string('document')->after('alternate_uuid')->nullable();
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
