<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiseaseToDosagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dosages', function (Blueprint $table) {
            $table->json('gain_pheno_omim')->nullable()->after('triplo_history');
            $table->string('gain_pheno_ontology')->nullable()->after('gain_pheno_omim');
            $table->string('gain_pheno_ontology_id')->nullable()->after('gain_pheno_ontology');
            $table->text('gain_comments')->nullable()->after('gain_pheno_ontology_id');
            $table->string('gain_pheno_name')->nullable()->after('gain_comments');
            $table->json('loss_pheno_omim')->nullable()->after('haplo_history');
            $table->string('loss_pheno_ontology')->nullable()->after('loss_pheno_omim');
            $table->string('loss_pheno_ontology_id')->nullable()->after('loss_pheno_ontology');
            $table->text('loss_comments')->nullable()->after('loss_pheno_ontology_id');
            $table->string('loss_pheno_name')->nullable()->after('loss_comments');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dosages', function (Blueprint $table) {
            //
        });
    }
}
