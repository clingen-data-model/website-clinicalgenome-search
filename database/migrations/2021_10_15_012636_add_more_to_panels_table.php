<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreToPanelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panels', function (Blueprint $table) {
            $table->renameColumn('curie', 'affiliate_id');
            $table->string('title')->after('name');
            $table->string('title_short')->after('title');
            $table->string('title_abbreviated')->after('title');
            $table->string('affiliate_type')->after('description');
            $table->json('affiliate_status')->nullable()->after('affiliate_type');
            $table->string('cdwg_parent_name')->nullable()->after('affiliate_status');
            $table->json('member')->nullable()->after('cdwg_parent_name');
            $table->renameColumn('description', 'summary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('panels', function (Blueprint $table) {
            //
        });
    }
}
