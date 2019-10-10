<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{

    const TABLE_NAME = 'payments';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->uuid('payment_id');
            $table->primary('payment_id');

            $table->uuidMorphs('sellable');

            $table->decimal('cost', 15, 2)->default(0.00);
            $table->unsignedTinyInteger('quantity')->default(1);
            $table->unsignedTinyInteger('discount')->default(0);

            $table->string('status')->default(\App\Enums\PaymentStatus::PENDING);
            $table->string('method')->default(\App\Enums\PaymentMethod::CASH);

            $table->timestamps();

            $table->softDeletes();
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
