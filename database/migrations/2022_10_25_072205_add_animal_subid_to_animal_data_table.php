<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnimalSubidToAnimalDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animal_data', function (Blueprint $table) {
            $table->string('animal_subid', 100)->after('animal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animal_data', function (Blueprint $table) {
           Schema::dropIfExists('animal_subid');
        });
    }
}
