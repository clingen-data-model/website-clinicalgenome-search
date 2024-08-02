<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurationPanelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curation_panel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curation_id');
            $table->foreignId('panel_id');
            $table->integer('level');
            $table->timestamps();

            $table->index('panel_id');
            $table->index('curation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curation_panel');
    }
}
