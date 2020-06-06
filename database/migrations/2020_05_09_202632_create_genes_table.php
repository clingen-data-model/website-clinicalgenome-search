<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('genes', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('ident')->unique();
			$table->string('name')->index('name');
			$table->string('hgnc_id')->index('hgnc_id');
			$table->string('description')->nullable();
			$table->string('location')->nullable();
			$table->jsonb('alias_symbol')->nullable();
			$table->jsonb('prev_symbol')->nullable();
			$table->string('date_symbol_changed')->nullable();
			$table->string('hi')->nullable();
			$table->string('plof')->nullable();
			$table->string('pli')->nullable();
			$table->string('haplo')->nullable();
			$table->string('triplo')->nullable();
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
		Schema::drop('genes');
	}
}
