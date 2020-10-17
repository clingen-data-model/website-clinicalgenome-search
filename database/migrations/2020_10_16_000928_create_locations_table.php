<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ident')->unique();
            $table->unsignedBigInteger('gene_id')->nullable();
            $table->foreign('gene_id')->references('id')->on('genes')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->string('cytoband')->unique();
            $table->string('chromosome');
            $table->integer('stop');
            $table->integer('start');
            $table->string('stain');
            $table->tinyInteger('type')->default(0);
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
        Schema::dropIfExists('locations');
    }
}
