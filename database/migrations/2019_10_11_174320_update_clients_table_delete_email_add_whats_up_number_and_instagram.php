<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateClientsTableDeleteEmailAddWhatsUpNumberAndInstagram extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('instagram')->nullable()->unique();
            $table->string('whats_up_number')->nullable()->unique();

            $table->dropColumn(['email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['instagram']);
            $table->dropColumn(['whats_up_number']);

            $table->string('email')->nullable()->unique();
        });
    }
}
