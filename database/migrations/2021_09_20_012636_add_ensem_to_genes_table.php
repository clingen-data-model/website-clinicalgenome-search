<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnsemToGenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('genes', function (Blueprint $table) {
            $table->index('ensembl_gene_id');
            $table->json('lsdb')->nullable()->after('locus_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('changes', function (Blueprint $table) {
            //
        });
    }
}
