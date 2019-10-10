<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupRecursTable extends Migration
{

    const TABLE_NAME = 'group_recurs';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('group_recur_id');

            $table->primary('group_recur_id');

            $table->uuid('group_id');
            $table->foreign('group_id')->references('group_id')->on('groups')->onDelete('cascade');

            $table->dateTime('start_date')->useCurrent();
            $table->dateTime('end_date')->default(DB::raw('current_timestamp + interval \'1 hour\''));

            $table->string('recurrence_type')->nullable();
            $table->dateTime('recurrence_until')->default(DB::raw('current_timestamp + interval \'2 weeks\''));

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
        Schema::table(static::TABLE_NAME, function (Blueprint $table) {
            $table->dropForeign(['group_id']);
        });

        Schema::dropIfExists(static::TABLE_NAME);
    }
}
