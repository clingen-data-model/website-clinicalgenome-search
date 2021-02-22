<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->tinyInteger('changed')->default(0);
			$table->json('validity')->nullable();
			$table->json('actionability')->nullable();
            $table->json('dosage')->nullable();
            $table->json('variant')->nullable();
            $table->json('pharma')->nullable();
            $table->json('spare1')->nullable();
            $table->json('spare2')->nullable();
			$table->tinyInteger('type')->default(0);
			$table->tinyInteger('status')->default(0);
			$table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
}
