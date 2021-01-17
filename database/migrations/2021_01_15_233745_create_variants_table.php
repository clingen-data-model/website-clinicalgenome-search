<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->string('iri');
            $table->string('variant_id');
            $table->string('caid')->nullable();
            $table->json('condition')->nullable();
            $table->json('evidence_links')->nullable();
            $table->json('gene')->nullable();
            $table->json('guidelines')->nullable();
            $table->json('hgvs')->nullable();
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
        Schema::dropIfExists('variants');
    }
}
