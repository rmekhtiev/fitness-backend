<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;

class HallController extends Controller
{
    public static $model = Hall::class;

    public static $parentModel = null;

    public static $transformer = null;
}
