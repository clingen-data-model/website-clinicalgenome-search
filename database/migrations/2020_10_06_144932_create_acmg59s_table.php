<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcmg59sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acmg59s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ident')->unique();
            $table->string('pheno');
            $table->string('omims');
            $table->string('pmids')->nullable();
            $table->string('age')->nullable();
            $table->string('gene')->nullable();
            $table->string('omimgene')->nullable();
            $table->string('gain')->nullable();
            $table->string('loss')->nullable();
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
        Schema::dropIfExists('acmg59s');
    }
}
