<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLockerBookingsTable extends Migration
{

    const TABLE_NAME = 'locker_bookings';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('booking_id');

            $table->uuid('locker_id');
            $table->uuid('client_id');

            $table->date('book_start');
            $table->date('book_end');

            $table->primary('booking_id');

            $table->foreign('locker_id')->references('locker_id')->on('lockers');
            $table->foreign('client_id')->references('client_id')->on('clients');

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
