<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOmimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('omims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ident')->unique();
            $table->string('prefix');
            $table->string('omimid')->unique();
            $table->string('titles', 1024);
            $table->string('alt_titles', 1024)->nullable();
            $table->string('inc_titles', 1024)->nullable();
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
        Schema::dropIfExists('omims');
    }
}
