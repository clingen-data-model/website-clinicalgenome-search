<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actionabilities', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->string('gene_label');
            $table->string('gene_hgnc_id')->index();
            $table->string('disease_label')->nullable();
            $table->string('disease_mondo')->index();
            $table->timestamp('adult_report_date', 4)->nullable();
            $table->string('adult_source')->nullable();
            $table->string('adult_classification')->nullable();
            $table->string('adult_attributed_to')->nullable();
            $table->timestamp('pediatric_report_date', 4)->nullable();
            $table->string('pediatric_source')->nullable();
            $table->string('pediatric_classification')->nullable();
            $table->string('pediatric_attributed_to')->nullable();
            $table->json('other')->nullable();
            $table->integer('version')->default(0);
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
        Schema::dropIfExists('actionabilities');
    }
}
