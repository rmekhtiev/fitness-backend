<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{

    public static $model = Payment::class;

    public static $parentModel = null;

    public static $transformer = null;
}
