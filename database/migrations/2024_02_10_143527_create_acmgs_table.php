<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcmgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acmgs', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->tinyInteger('type')->default(0);
            $table->foreignId('gene_id')->constrained();
            $table->string('gene_symbol');
            $table->string('gene_mim')->nullable();
            $table->foreignId('disease_id')->constrained();
            $table->string('disease_name');
            $table->json('disease_mims')->nullable();
            $table->json('documents')->nullable();
            $table->json('demographics')->nullable();
            $table->json('scores')->nullable();
            $table->string('clinvar_link');
            $table->tinyInteger('is_curated')->default(0);
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
        Schema::dropIfExists('acmgs');
    }
}
