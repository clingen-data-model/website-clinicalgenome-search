<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePubmedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pubmeds', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(0);
            $table->tinyInteger('subtype')->default(0);
            $table->integer('pmid');
            $table->text('description')->nullable();
            $table->string('evidence_type')->nullable();
            $table->tinyInteger('status')->default(0);
			$table->softDeletes();
            $table->timestamps();

            $table->index('type');
            $table->index('pmid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pubmeds');
    }
}
