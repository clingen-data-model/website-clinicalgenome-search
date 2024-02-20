<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packets', function (Blueprint $table) {
            $table->id();$table->string('ident')->unique();
            $table->tinyInteger('type')->default(0);
            $table->string('topic');
            $table->string('uuid')->nullable();
            $table->integer('timestamp')->default(0);
            $table->integer('offset')->default(0);
            $table->jsonb('payload')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('topic');
            $table->index('uuid');
            $table->index(['topic', 'offset']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packets');
    }
}
