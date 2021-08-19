<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenccsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genccs', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->string('uuid')->index();
            $table->string('gene_curie');
            $table->string('gene_symbol');
            $table->string('disease_curie');
            $table->string('disease_title');
            $table->string('disease_original_curie');
            $table->string('disease_original_title');
            $table->string('classification_curie');
            $table->string('classification_title');
            $table->string('moi_curie');
            $table->string('moi_title');
            $table->string('submitter_curie');
            $table->string('submitter_title');
            $table->string('submitted_as_date');
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
        Schema::dropIfExists('genccs');
    }
}
