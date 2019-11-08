<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeGroupRecursBeSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('group_recurs', 'schedules');

        Schema::table('schedules', function (Blueprint $table) {
            $table->renameColumn('group_recur_id', 'schedule_id');
            $table->renameColumn('group_id', 'schedulable_id');
            $table->string("schedulable_type")->nullable();
        });

        \App\Models\Schedule::whereNull('schedulable_type')->update(['schedulable_type' => 'groups']);

        Schema::table('schedules', function (Blueprint $table) {
            $table->string("schedulable_type")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('schedules', 'group_recurs');

        Schema::table('group_recurs', function (Blueprint $table) {
            $table->renameColumn('schedule_id', 'group_recur_id');
            $table->renameColumn('schedulable_id', 'group_id');
            $table->dropColumn(["schedulable_type"]);
        });


    }
}
