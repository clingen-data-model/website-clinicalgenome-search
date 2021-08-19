<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGtrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gtrs', function (Blueprint $table) {
            $table->id();
            $table->string('ident')->unique();
            $table->tinyInteger('type')->default(0);
            $table->string('test_accession_ver');
            $table->string('<name_of_lab></name_of_lab>oratory');
            $table->string('name_of_institution')->nullable();
            $table->string('facility_state')->nullable();
            $table->string('facility_postcode')->nullable();
            $table->string('facility_country')->nullable();
            $table->string('CLIA_number')->nullable();
            $table->json('state_licenses')->nullable();
            $table->json('state_license_numbers')->nullable();
            $table->string('lab_test_id')->nullable();
            $table->date('last_touch_date')->nullable();
            $table->string('lab_test_name')->nullable();
            $table->string('manufacturer_test_name')->nullable();
            $table->string('test_development')->nullable();
            $table->string('lab_unique_code')->nullable();
            $table->json('condition_identifiers')->nullable();
            $table->json('indication_types')->nullable();
            $table->json('inheritances')->nullable();
            $table->json('method_categories')->nullable();
            $table->json('methods')->nullable();
            $table->json('platforms')->nullable();
            $table->json('genes')->nullable();
            $table->json('drug_responses')->nullable();
            $table->tinyInteger('now_current');
            $table->integer('test_currStat');
            $table->integer('test_pubStat');
            $table->integer('lab_currStat');
            $table->integer('lab_pubStat');
            $table->date('test_create_date');
            $table->date('test_deletion_data')->nullable();
            $table->integer('version')->default(1);
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
        Schema::dropIfExists('gtrs');
    }
}
