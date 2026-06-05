<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SwapTermsNameFulltextWordParser extends Migration
{
    /**
     * Run the migrations.
     *
     * The bigram (ngram) FULLTEXT index can't selectively run substring
     * searches on a table this size — common bigrams overflow InnoDB's FTS
     * result cache (error 188).  Replace it with a default word-parser
     * FULLTEXT index so the suggester can do fast word-prefix matching
     * (AGAINST('ehler*') etc.).
     *
     * @return void
     */
    public function up()
    {
        // drop the ngram index from the earlier migration if present
        $exists = DB::selectOne(
            "SELECT 1 FROM information_schema.statistics
             WHERE table_schema = DATABASE() AND table_name = 'terms'
               AND index_name = 'ft_name' LIMIT 1"
        );

        if ($exists)
            DB::statement('ALTER TABLE terms DROP INDEX ft_name');

        DB::statement('ALTER TABLE terms ADD FULLTEXT ft_name (name)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $exists = DB::selectOne(
            "SELECT 1 FROM information_schema.statistics
             WHERE table_schema = DATABASE() AND table_name = 'terms'
               AND index_name = 'ft_name' LIMIT 1"
        );

        if ($exists)
            DB::statement('ALTER TABLE terms DROP INDEX ft_name');

        DB::statement('ALTER TABLE terms ADD FULLTEXT ft_name (name) WITH PARSER ngram');
    }
}
