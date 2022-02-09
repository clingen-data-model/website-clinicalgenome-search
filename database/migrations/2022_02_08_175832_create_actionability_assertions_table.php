<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionabilityAssertionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actionability_assertions', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->tinyInteger('type')->default(0)->index();
            $table->string("docid");
            $table->string("iri");
            $table->string("latest_search_date");
            $table->string("last_updated");
            $table->string("last_author");
            $table->string("context");
            $table->string("contextiri");
            $table->string("release")->nullable();
            $table->string("gene")->index();
            $table->string("gene_omim")->index();
            $table->string("disease")->index();
            $table->string("omim")->index();
            $table->string("mondo")->nullable()->index();
            $table->string("consensus_assertion")->index();
            $table->string("status_assertion");
            $table->string("status_overall");
            $table->string("status_stg1");
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actionability_assertions');
    }
}
