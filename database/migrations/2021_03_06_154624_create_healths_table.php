<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('healths', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->string('service')->unique();
            $table->string('subservice')->nullable();
            $table->string('internal')->nullable();
            $table->string('genegraph')->nullable();
            $table->string('dci')->nullable();
            $table->string('spare1')->nullable();
            $table->string('spare2')->nullable();
            $table->string('spare3')->nullable();
            $table->string('spare4')->nullable();
            $table->string('spare5')->nullable();
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
        Schema::dropIfExists('healths');
    }
}
