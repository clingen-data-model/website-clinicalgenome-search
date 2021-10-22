<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrecurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precurations', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->tinyInteger('type')->default(0);
            $table->bigInteger('gtid');
            //$table->string('event_type');
            $table->string('gt_uuid');
            $table->string('gdm_uuid')->nullable();
            $table->string('hgnc_id');
            $table->string('mondo_id')->nullable();
            $table->string('hp_id')->nullable();
            $table->string('group_id');
            $table->json('group_detail')->nullable();
            $table->json('curator_detail')->nullable();
            $table->timestamp('date_uploaded')->nullable();
            $table->timestamp('date_precuration')->nullable();
            $table->timestamp('date_disease_assigned')->nullable();
            $table->timestamp('date_precuration_complete')->nullable();
            $table->timestamp('date_curation_provisional')->nullable();
            $table->timestamp('date_curation_approved')->nullable();
            $table->timestamp('date_retired')->nullable();
            $table->timestamp('date_published')->nullable();
            $table->json('rationale')->nullable();
            $table->json('curation_type')->nullable();
            $table->json('omim_phenotypes')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('precurations');
    }
}
