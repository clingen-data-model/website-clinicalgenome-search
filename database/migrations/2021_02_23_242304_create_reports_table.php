<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('title_id')->constrained();
            $table->tinyInteger('type')->default(0);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('stop_date')->nullable();
            $table->json('filters')->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('reports');
    }
}
