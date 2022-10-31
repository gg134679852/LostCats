<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCompleteToDonateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donate_logs', function (Blueprint $table) {
            $table->boolean('isComplete')->after('donate_price')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donate_logs', function (Blueprint $table) {
           Schema::dropIfExists('isComplete');
        });
    }
}
