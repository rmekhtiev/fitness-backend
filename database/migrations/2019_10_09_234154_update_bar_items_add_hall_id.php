<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBarItemsAddHallId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bar_items', function (Blueprint $table) {
            $table->uuid('hall_id');

            $table->foreign('hall_id')->references('hall_id')->on('halls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bar_items', function (Blueprint $table) {
            $table->dropForeign(['hall_id']);
            $table->dropColumn(['hall_id']);
        });
    }
}
