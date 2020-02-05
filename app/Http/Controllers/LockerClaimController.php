<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\Locker;
use App\Transformers\BaseTransformer;
use Illuminate\Http\Request;
use App\Models\LockerClaim;

class LockerClaimController extends Controller
{

    public static $model = LockerClaim::class;

    public static $parentModel = null;

    public static $transformer = null;
}
