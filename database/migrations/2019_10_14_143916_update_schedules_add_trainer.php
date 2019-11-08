<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSchedulesAddTrainer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->uuid('trainer_id')->nullable();
        });

        /** @var \App\Models\Schedule[] $schedules */
        $schedules = \App\Models\Schedule::whereNull('trainer_id')
            ->where('schedulable_type', 'groups')
            ->with(['schedulable'])
            ->get();

        foreach ($schedules as $schedule) {
            $schedule->update([
                'trainer_id' => $schedule->schedulable->trainer_id
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['trainer_id']);
        });
    }
}
