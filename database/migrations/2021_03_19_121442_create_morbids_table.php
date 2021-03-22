<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMorbidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('morbids', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->string('phenotype')->nullable();
            $table->string('mim')->nullable();
            $table->string('mapkey')->nullable();
            $table->string('disputing')->nullable();
            $table->json('genes')->nullable();
            $table->string('cyto')->nullable();
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
        Schema::dropIfExists('morbids');
    }
}
