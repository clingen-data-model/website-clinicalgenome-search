<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantpathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variantpaths', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->string('curie')->index();
            $table->string('vid')->index();
            $table->timestamp('report_date', 4)->index();
            $table->string('disease_label');
            $table->string('disease_mondo')->index();
            $table->string('gene_label');
            $table->string('gene_hgnc_id')->index();
            $table->string('classification')->nullable();
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
        Schema::dropIfExists('variantpaths');
    }
}
