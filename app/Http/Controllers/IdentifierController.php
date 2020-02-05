<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Identifier;

class IdentifierController extends Controller
{
    public static $model = Identifier::class;

    public static $parentModel = null;

    public static $transformer = null;
}
