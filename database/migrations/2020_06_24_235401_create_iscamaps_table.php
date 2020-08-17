<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIscamapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iscamaps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ident')->unique();
			$table->string('symbol')->index('symbol');
            $table->string('issue')->index('issue');
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
        Schema::dropIfExists('iscamaps');
    }
}
