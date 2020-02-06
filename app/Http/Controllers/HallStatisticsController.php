<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use App\Transformers\HallStatisticsTransformer;
use Illuminate\Http\Request;

class HallStatisticsController extends Controller
{
    public static $model = Hall::class;

    public static $parentModel = null;

    public static $transformer = HallStatisticsTransformer::class;
}
