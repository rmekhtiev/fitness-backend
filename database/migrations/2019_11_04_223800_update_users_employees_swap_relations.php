<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersEmployeesSwapRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('employee_id')->nullable();
            $table->foreign('employee_id')->references('employee_id')->on('employees')->onDelete('set null');
        });

        $users = DB::table('users')->join('employees', 'user_id', 'associated_user_id');
        $users->update(['employee_id' => DB::raw('employees.employee_id')]);

        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['associated_user_id']);
            $table->dropColumn(['associated_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->uuid('associated_user_id')->nullable();
            $table->foreign('associated_user_id')->references('user_id')->on('users')->onDelete('set null');
        });

        $users = DB::table('employees')->join('users', 'employees.employee_id', 'users.employee_id');
        $users->update(['associated_user_id' => DB::raw('users.user_id')]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn(['employee_id']);
        });
    }
}
