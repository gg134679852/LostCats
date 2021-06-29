<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_data', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id');
            $table->string('animal_place');
            $table->string('animal_sex');
            $table->string('animal_age');
            $table->string('animal_bodytype');
            $table->string('animal_colour');
            $table->string('animal_sterilization');
            $table->string('animal_bacterin');
            $table->string('animal_foundplace');
            $table->string('shelter_name');
            $table->string('album_file');
            $table->string('shelter_address');
            $table->string('shelter_tel');
            $table->string('animal_remark');
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
        Schema::dropIfExists('animal_data');
    }
}
