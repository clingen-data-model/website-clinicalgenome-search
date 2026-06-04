<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddFulltextToTermsName extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds an ngram FULLTEXT index on terms.name so the condition
     * autocomplete can do substring matching (MATCH ... AGAINST) from an
     * index instead of a full table scan driven by LIKE '%...%'.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE terms ADD FULLTEXT ft_name (name) WITH PARSER ngram');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE terms DROP INDEX ft_name');
    }
}
