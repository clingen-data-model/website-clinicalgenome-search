<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenomeconnectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genomeconnects', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->tinyInteger('type')->default(0);
            $table->foreignId('gene_id')->constrained();
            $table->integer('variant_count')->default(0);
            $table->json('clinvar_ids')->nullable()->defaut(null);
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
        Schema::dropIfExists('genomeconnects');
    }
}
