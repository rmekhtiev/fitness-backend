<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\Locker;
use App\Transformers\BaseTransformer;
use Illuminate\Http\Request;
use App\Models\LockerClaim;

class LockerClaimController extends Controller
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = LockerClaim::class;

    /**
     * @var BaseModel The parent model of the model, in the case of a child rest controller
     */
    public static $parentModel = Locker::class;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;
}
