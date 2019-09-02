<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Transformers\BaseTransformer;
use Illuminate\Http\Request;
use App\Models\Locker;

class LockerController extends Controller
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = Locker::class;

    /**
     * @var BaseModel The parent model of the model, in the case of a child rest controller
     */
    public static $parentModel = null;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;
}
