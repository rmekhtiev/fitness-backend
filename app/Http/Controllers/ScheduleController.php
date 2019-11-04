<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\Schedule;
use App\Transformers\BaseTransformer;
use Illuminate\Http\Request;
use App\Models\Hall;

class ScheduleController extends Controller
{
    /**
     * @var Schedule|BaseModel The primary model associated with this controller
     */
    public static $model = Schedule::class;

    /**
     * @var Schedule|BaseModel The parent model of the model, in the case of a child rest controller
     */
    public static $parentModel = null;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;
}
