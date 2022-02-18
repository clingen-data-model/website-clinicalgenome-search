<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmidsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pmids', function(Blueprint $table)
		{
			$table->id();
			$table->string('ident')->unique();
			$table->integer('pmid')->index();
			$table->integer('uid')->inex();
			$table->string('pubdate')->nullable();
			$table->string('epubdate')->nullable();
			$table->string('source')->nullable();
			$table->text('authors')->nullable();
			$table->string('lastauthor')->nullable();
			$table->string('title')->nullable();
			$table->text('abstract')->nullable();
			$table->string('sorttitle')->nullable();
			$table->string('volume')->nullable();
			$table->string('issue')->nullable();
			$table->string('pages')->nullable();
			$table->string('lang')->nullable();
			$table->string('nlmuniqueid')->nullable();
			$table->string('issn')->nullable();
			$table->string('essn')->nullable();
			$table->string('pubtype')->nullable();
			$table->string('recordstatus')->nullable();
			$table->string('pubstatus')->nullable();
			$table->text('articleids')->nullable();
			$table->text('history')->nullable();
			$table->text('references')->nullable();
			$table->string('attributes')->nullable();
			$table->string('pmcrefcount')->nullable();
			$table->string('fullfournalname')->nullable();
			$table->string('elocationid')->nullable();
			$table->string('doctype')->nullable();
			$table->text('srccontriblist')->nullable();
			$table->string('booktitle')->nullable();
			$table->string('medium')->nullable();
			$table->string('edition')->nullable();
			$table->string('publisherlocation')->nullable();
			$table->string('publishername')->nullable();
			$table->string('srcdate')->nullable();
			$table->string('reportnumber')->nullable();
			$table->string('availablefromurl')->nullable();
			$table->string('locationlabel')->nullable();
			$table->text('doccontriblist')->nullable();
			$table->string('docdate')->nullable();
			$table->string('bookname')->nullable();
			$table->string('chapter')->nullable();
			$table->string('sortpubdate')->nullable();
			$table->string('sortfirstauthor')->nullable();
			$table->string('vernaculartitle')->nullable();
            $table->json('mesh_terms')->nullable();
			$table->text('other')->nullable();
            $table->integer('priority')->default(0);
			$table->mediumText('efetch')->nullable();
			$table->mediumText('notes')->nullable();
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
		Schema::drop('pmids');
	}
}
