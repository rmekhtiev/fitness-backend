<?php


namespace App\Models;


use App\Enums\PaymentMethod;

interface PaymentSubject
{
    public function payments();

    public function sell($paymentMethod = PaymentMethod::CASH, $quantity = 1);
}
