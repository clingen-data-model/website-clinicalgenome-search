<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->tinyInteger('type')->default(0);
            $table->tinyInteger('subtype')->default(0);
            $table->string('source');
            $table->string('source_uuid');
            $table->string('alternate_uuid');
            $table->tinyInteger('activity')->default(0);
            $table->string('activity_string');
            $table->json('references')->nullable();
            $table->json('affiliation')->nullable();
            $table->json('workflow')->nullable();
            $table->json('version')->nullable();
            $table->json('changes')->nullable();
            $table->json('notes')->nullable();
            $table->json('urls')->nullable();
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
        Schema::dropIfExists('activities');
    }
}
