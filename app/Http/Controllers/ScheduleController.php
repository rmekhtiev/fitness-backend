<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\Schedule;
use App\Transformers\BaseTransformer;
use Illuminate\Http\Request;
use App\Models\Hall;

class ScheduleController extends Controller
{
    public static $model = Schedule::class;

    public static $parentModel = null;

    public static $transformer = null;
}
