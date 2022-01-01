<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gene_id')->nullable();
            $table->tinyInteger('type')->default(0);
            $table->string('curation_type');
            $table->string('original_gene_symbol')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('on_180_chip')->default(0);
            $table->tinyInteger('reduced_penetrance')->default(0);
            $table->text('reduced_penetrance_comment')->nullable();
            $table->string('loss_phenotype_id')->nullable();
            $table->string('loss_phenotype_specificity')->nullable();
            $table->string('loss_phenotype_name')->nullable();
            $table->text('loss_phenotype_comment')->nullable();
            $table->string('gain_phenotype_id')->nullable();
            $table->string('gain_phenotype_specificity')->nullable();
            $table->string('gain_phenotype_name')->nullable();
            $table->text('gain_phenotype_comment')->nullable();
            $table->string('haploinsufficiency_score')->nullable();
            $table->string('triplosensitivity_score')->nullable();
            $table->string('targeting_decision')->nullable();
            $table->string('targeting_basis')->nullable();
            $table->text('targeting_comment')->nullable();
            //DDG2P stuff?
            $table->string('cgd_condition')->nullable();
            $table->string('cgd_inheritance')->nullable();
            $table->text('cgd_references')->nullable();
            $table->string('curation_status')->nullable();
            $table->string('resolution')->nullable();
            $table->string('curator')->nullable();
            $table->text('comment')->nullable();
            $table->dateTime('created_date')->nullable();
            $table->dateTime('update_date')->nullable();
            $table->dateTime('resolved_date')->nullable();
            $table->dateTime('reopened_date')->nullable();
            $table->integer('version')->default(0);
            $table->tinyInteger('status')->default(0);
			$table->softDeletes();
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
        Schema::dropIfExists('curations');
    }
}
