<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensitivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensitivities', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->string('curie')->index();
            $table->timestamp('report_date', 4)->index();
            $table->string('gene_label');
            $table->string('gene_hgnc_id')->index();
            $table->string('haplo_disease_label')->nullable();
            $table->string('haplo_disease_mondo')->nullable();
            $table->string('haplo_classification')->nullable();
            $table->json('haplo_other')->nullable();
            $table->string('triplo_disease_label')->nullable();
            $table->string('triplo_disease_mondo')->nullable();
            $table->string('triplo_classification')->nullable();
            $table->json('triplo_other')->nullable();
            $table->string('specified_by')->nullable();
            $table->string('attributed_to')->nullable();
            $table->integer('version')->default(0);
			$table->tinyInteger('type')->default(0);
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
        Schema::dropIfExists('sensitivities');
    }
}
