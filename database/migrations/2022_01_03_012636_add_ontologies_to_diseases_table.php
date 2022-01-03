<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOntologiesToDiseasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diseases', function (Blueprint $table) {
            $table->string('orpha_id')->nullable()->after('omim')->index();
            $table->string('do_id')->nullable()->after('orpha_id')->index();
            $table->string('medgen_id')->nullable()->after('do_id')->index();
            $table->string('gard_id')->nullable()->after('medgen_id')->index();
            $table->string('umls_id')->nullable()->after('gard_id')->index();
            $table->index('curie');
            $table->index('omim');
            $table->index('label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('streams', function (Blueprint $table) {
            //
        });
    }
}
