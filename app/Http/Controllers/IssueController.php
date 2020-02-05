<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;

class IssueController extends Controller
{

    public static $model = Issue::class;

    public static $parentModel = null;

    public static $transformer = null;
}
