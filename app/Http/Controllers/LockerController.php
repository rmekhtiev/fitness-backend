<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Transformers\BaseTransformer;
use Illuminate\Http\Request;
use App\Models\Locker;

class LockerController extends Controller
{

    public static $model = Locker::class;

    public static $parentModel = null;

    public static $transformer = null;
}
