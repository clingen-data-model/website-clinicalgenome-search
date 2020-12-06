<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDosagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dosages', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('ident')->unique();
			$table->string('label')->index('name');
            $table->string('issue')->index('issue');
            $table->string('curation')->nullable();
            $table->text('description')->nullable();
            $table->string('cytoband')->nullable();
            $table->string('chr')->nullable();
            $table->integer('start')->nullable();
            $table->integer('stop')->nullable();
            $table->integer('start38')->nullable();
            $table->integer('stop38')->nullable();
            $table->string('grch37')->nullable();
            $table->string('grch38')->nullable();
            $table->string('pli')->nullable();
            $table->string('omim')->nullable();
			$table->string('haplo')->nullable();
            $table->string('triplo')->nullable();
            $table->json('history')->nullable();
            $table->string('workflow')->nullable();
            $table->string('resolved')->nullable();
			$table->mediumText('notes')->nullable();
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
        Schema::dropIfExists('dosages');
    }
}
