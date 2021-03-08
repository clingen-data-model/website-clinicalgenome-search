<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCredentialsToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname')->nullable()->after('name');
            $table->string('lastname')->nullable()->after('firstname');
            $table->string('organization')->nullable()->after('lastname');
            $table->json('profile')->nullable()->after('organization');
            $table->json('preferences')->nullable()->after('profile');
            $table->string('avatar')->nullable()->after('preferences');
            $table->string('credentials')->nullable()->after('lastname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
