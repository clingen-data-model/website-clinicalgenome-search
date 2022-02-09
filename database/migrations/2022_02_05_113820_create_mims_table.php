<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mims', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->tinyInteger('type')->default(0);
            $table->integer('mim')->index();
            $table->string('gene_name')->index();
            $table->foreignId('gene_id');
            $table->string('title');
            $table->string('moi')->nullable();
            $table->string('map_key')->nullable();
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
        Schema::dropIfExists('mims');
    }
}
