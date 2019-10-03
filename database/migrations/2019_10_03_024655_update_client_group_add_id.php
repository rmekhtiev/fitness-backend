<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateClientGroupAddId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_group', function (Blueprint $table) {
            $table->uuid('client_group_id');

            $table->index([
                'client_id',
                'group_id'
            ], 'composite_id');

            $table->dropPrimary();
            $table->primary('client_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_group', function (Blueprint $table) {
            $table->dropColumn('client_group_id');

            $table->dropIndex('composite_id');

            $table->dropPrimary();
            $table->primary([
                'client_id',
                'group_id'
            ]);
        });
    }
}
