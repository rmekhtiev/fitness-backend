<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameBookingsToClaims extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('locker_bookings', 'locker_claims');

        Schema::table('locker_claims', function (Blueprint $table) {
            $table->renameColumn('booking_id', 'claim_id');
            $table->renameColumn('book_start', 'claim_start');
            $table->renameColumn('book_end', 'claim_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('locker_claims', 'locker_bookings');

        Schema::table('locker_bookings', function (Blueprint $table) {
            $table->renameColumn('claim_id', 'booking_id');
            $table->renameColumn('claim_start', 'book_start');
            $table->renameColumn('claim_end', 'book_end');
        });
    }
}
