<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTrainingSessionsAddDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropColumn(['count']);

            $table->dateTime('date_start')->default(DB::raw('current_timestamp'));
            $table->dateTime('date_end')->default(DB::raw('current_timestamp + interval \'1 month\''));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropColumn(['date_start', 'date_end']);

            $table->unsignedSmallInteger('count')->default(0);
        });
    }
}
