<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShelterList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shelter_list', function (Blueprint $table) {
            $table->id();
            $table->string('shelter_name', 100);
            $table->string('shelter_city',15);
            $table->string('shelter_address');
            $table->string('shelter_tel', 15);
            $table->string('shelter_lat');
            $table->string('shelter_lng');
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
        Schema::dropIfExists('shelter_list');
    }
}
