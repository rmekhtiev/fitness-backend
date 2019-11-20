<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTrainingSessionsAddCost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropColumn('payed_until');

            $table->decimal('cost', 15, 2)->default(0.00);
            $table->unsignedSmallInteger('count')->default(0);
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
            $table->dateTime('payed_until')->default(DB::raw('current_timestamp + interval \'1 month\''));

            $table->dropColumn(['cost', 'count']);
        });
    }
}
