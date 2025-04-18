<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportables', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->tinyInteger('type')->default(0);
            $table->string('gene_symbol');
            $table->string('gene_hgnc_id');
            $table->string('disease_name');
            $table->string('disease_mondo_id');
            $table->string('moi');
            $table->string('reportable');
            $table->string('comment');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('gene_hgnc_id');
            $table->index('disease_mondo_id');
            $table->index(['gene_hgnc_id', 'disease_mondo_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reportables');
    }
}
