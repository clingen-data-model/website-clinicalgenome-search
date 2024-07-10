<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('streams', function (Blueprint $table) {
//            //
//        });

        $dataToInsert = [
            [
                'name' => 'gpm-general-events',
                'topic' => 'gpm-general-events',
                'type' => 1,
                'description' => 'Stream for GPM general events',
                'offset' => 0,
                'parser' => \App\Panel::class,
            ],
            [
                'name' => 'gpm-person-events',
                'topic' => 'gpm-person-events',
                'type' => 1,
                'description' => 'Stream for GPM general events',
                'offset' => 0,
                'parser' => \App\Panel::class,

            ],

        ];

        foreach ($dataToInsert as $data) {
            $data['ident'] = \Illuminate\Support\Str::uuid();
            \Illuminate\Support\Facades\DB::table('streams')->insert(
                $data
            );
        }


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
