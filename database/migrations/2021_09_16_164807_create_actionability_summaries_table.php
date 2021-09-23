<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionabilitySummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actionability_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('docId')->nullable();
            $table->string('iri')->nullable();
            $table->string('latestSearchDate')->nullable();
            $table->string('lastUpdated')->nullable();
            $table->string('lastAuthor')->nullable();
            $table->string('context')->nullable();
            $table->string('contextIri')->nullable();
            $table->string('release')->nullable();
            $table->string('releaseDate')->nullable();
            $table->string('gene')->nullable();
            $table->string('geneOmim')->nullable();
            $table->string('disease')->nullable();
            $table->string('omim')->nullable();
            $table->string('status_overall')->nullable();
            $table->string('status_stg1')->nullable();
            $table->string('status_stg2')->nullable();
            $table->string('status_scoring')->nullable();
            $table->string('outcome')->nullable();
            $table->string('outcomeScoringGroup')->nullable();
            $table->string('intervention')->nullable();
            $table->string('interventionScoringGroup')->nullable();
            $table->string('scorer')->nullable();
            $table->string('prelim_severity')->nullable();
            $table->string('prelim_likelihood')->nullable();
            $table->string('prelim_natureOfIntervention')->nullable();
            $table->string('prelim_effectiveness')->nullable();
            $table->string('final_severity')->nullable();
            $table->string('final_likelihood')->nullable();
            $table->string('final_natureOfIntervention')->nullable();
            $table->string('final_effectiveness')->nullable();
            $table->string('final_overall')->nullable();
            $table->string('severity')->nullable();
            $table->string('likelihood')->nullable();
            $table->string('natureOfIntervention')->nullable();
            $table->string('effectiveness')->nullable();
            $table->string('overall')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actionability_summaries');
    }
}
