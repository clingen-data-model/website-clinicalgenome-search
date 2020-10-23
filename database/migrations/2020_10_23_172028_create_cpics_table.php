<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpics', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('ident')->unique();
			$table->string('gene')->index('gene');
			$table->string('drug')->index('drug');
			$table->string('guideline')->nullable();
			$table->string('cpic_level')->nullable();
			$table->string('cpic_level_status')->nullable();
			$table->string('pharmgkb_level_of_evidence')->nullable();
			$table->string('pgx_on_fda_label')->nullable();
			$table->string('cpic_publications_pmid')->nullable();
			$table->mediumText('notes')->nullable();
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
        Schema::dropIfExists('cpics');
    }
}
