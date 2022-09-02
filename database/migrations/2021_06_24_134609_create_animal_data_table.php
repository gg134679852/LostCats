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
            $table->string('animal_sex',6);
            $table->string('animal_age',12);
            $table->string('animal_bodytype',6);
            $table->string('animal_color',16);
            $table->string('animal_sterilization',9);
            $table->string('animal_bacterin',21);
            $table->string('animal_foundplace',100);
            $table->string('shelter_name',100);
            $table->string('album_file')->nullable($value = true);
            $table->string('shelter_address');
            $table->string('shelter_tel',15);
            $table->string('animal_remark',100)->nullable($value = true);
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
