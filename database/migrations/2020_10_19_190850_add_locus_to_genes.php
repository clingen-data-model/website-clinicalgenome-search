<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocusToGenes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('genes', function (Blueprint $table) {
            $table->string('locus_type')->nullable()->after('date_symbol_changed');
            $table->string('locus_group')->nullable()->after('date_symbol_changed');
            $table->string('ensembl_gene_id')->nullable()->after('omim_id');
            $table->string('entrez_id')->nullable()->after('ensembl_gene_id');
            $table->string('ucsc_id')->nullable()->after('entrez_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('genes', function (Blueprint $table) {
            
        });
    }
}
