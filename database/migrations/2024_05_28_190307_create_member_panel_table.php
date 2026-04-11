<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberPanelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_panel', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Panel::class, 'panel_id');
            $table->foreignIdFor(\App\Member::class, 'member_id');
            $table->string('process_wire_id')->nullable();
            $table->string('role')->nullable();
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
        Schema::dropIfExists('member_panel');
    }
}
