<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVisitHistoryRecordsTableChangeDatetime extends Migration
{
    const TABLE_NAME = 'visit_history_records';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visit_history_records', function (Blueprint $table) {
            $table->dateTime('datetime')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visit_history_records', function (Blueprint $table) {
            $table->date('datetime')->change();
        });
    }
}
