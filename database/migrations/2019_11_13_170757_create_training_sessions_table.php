<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingSessionsTable extends Migration
{

    const TABLE_NAME = 'training_sessions';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('training_session_id');

            $table->primary('training_session_id');

            $table->uuid('client_id');
            $table->foreign('client_id')->references('client_id')->on('clients')->onDelete('cascade');

            $table->uuid('trainer_id');
            $table->foreign('trainer_id')->references('trainer_id')->on('trainers')->onDelete('set null');

            $table->dateTime('payed_until')->default(DB::raw('current_timestamp + interval \'1 month\''));

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
