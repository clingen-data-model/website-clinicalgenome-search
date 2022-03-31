<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('curations');

        Schema::create('curations', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->tinyInteger('type')->default(0);
            $table->string('type_string')->nullable();
            $table->tinyInteger('subtype')->default(0);
            $table->string('subtype_string')->nullable();
            $table->integer('group_id')->default(0);
            $table->string('sop_version')->nullable();
            $table->string('source')->nullable();
            $table->string('source_uuid')->nullable()->index();
            $table->string('assertion_uuid')->nullable()->index();
            $table->string('alternate_uuid')->nullable()->index();
            $table->string('affiliate_id')->nullable()->index;
            $table->json('affiliate_details')->nullable();
            $table->string('gene_hgnc_id')->nullable()->index;
            $table->json('gene_details')->nullable();
            $table->string('title')->nullable();
            $table->text('summary')->nullable();
            $table->text('description')->nullable();
            $table->text('comments')->nullable();
            $table->json('conditions')->nullable();
            $table->json('condition_details')->nullable();
            $table->json('evidence')->nullable();
            $table->json('evidence_details')->nullable();
            $table->json('scores')->nullable();
            $table->json('score_details')->nullable();
            $table->json('curators')->nullable();
            $table->tinyInteger('published')->default(0);
            $table->tinyInteger('animal_model_only')->default(0);
            $table->json('events')->nullable();
            $table->integer('version')->default(0);
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
        //
    }
}
