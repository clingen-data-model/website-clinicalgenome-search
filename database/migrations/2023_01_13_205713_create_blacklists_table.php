<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlacklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blacklists', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->tinyInteger('type')->default(0);
            $table->string('gci_id')->index();
            $table->string('gg_id')->index();
            $table->string('gene_symbol');
            $table->string('disease');
            $table->string('moi');
            $table->string('gcep');
            $table->string('coordinator');
            $table->string('grant');
            $table->string('notes');
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
        Schema::dropIfExists('blacklists');
    }
}
