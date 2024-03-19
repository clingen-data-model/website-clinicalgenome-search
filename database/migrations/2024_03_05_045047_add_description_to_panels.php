<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToPanels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panels', function (Blueprint $table) {
            //
            $table->text('description')->nullable();
            $table->string('clinvar_org_id')->nullable();
            $table->text('url_clinvar')->nullable();
            $table->text('url_cspec')->nullable();
            $table->text('url_curations')->nullable();
            $table->text('url_erepo')->nullable();
            $table->string('expert_panel_status')->nullable();
            $table->dateTime('status_define_group_date')->nullable();
            $table->dateTime('status_class_rules_date')->nullable();
            $table->dateTime('status_pilot_rules_date')->nullable();
            $table->dateTime('status_approval_date')->nullable();
            $table->dateTime('status_inactive_date')->nullable();
            $table->boolean('is_inactive')->nullable();
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
            $table->dropColumn('description');
            $table->dropColumn('clinvar_org_id');
            $table->dropColumn('url_clinvar');
            $table->dropColumn('url_cspec');
            $table->dropColumn('url_curations');
            $table->dropColumn('url_erepo');
            $table->dropColumn('expert_panel_status');
            $table->dropColumn('status_define_group_date');
            $table->dropColumn('status_class_rules_date');
            $table->dropColumn('status_pilot_rules_date');
            $table->dropColumn('status_approval_date');
            $table->dropColumn('status_inactive_date');
            $table->dropColumn('is_inactive');
        });
    }
}
