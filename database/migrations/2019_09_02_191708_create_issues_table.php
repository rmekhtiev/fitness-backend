<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{

    const TABLE_NAME = 'issues';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('issue_id');
            $table->string('description');
            $table->string('status');

            $table->uuid('hall_id')->nullable();
            $table->uuid('user_id')->nullable();

            $table->foreign('hall_id')->references('hall_id')->on('halls');
            $table->foreign('user_id')->references('user_id')->on('users');

            $table->primary('issue_id');

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
