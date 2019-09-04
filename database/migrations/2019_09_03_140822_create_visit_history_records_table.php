<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitHistoryRecordsTable extends Migration
{

    const TABLE_NAME = 'visit_history_records';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('record_id');
            $table->date('datetime');

            $table->uuid('client_id');
            $table->uuid('hall_id');

            $table->foreign('client_id')->references('client_id')->on('clients');
            $table->foreign('hall_id')->references('hall_id')->on('halls');

            $table->primary('');

            $table->timestamps();
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(static::TABLE_NAME);
    }
}
