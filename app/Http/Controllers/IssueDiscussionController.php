<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IssueDiscussion;

class IssueDiscussionController extends Controller
{

    public static $model = IssueDiscussion::class;

    public static $parentModel = null;

    public static $transformer = null;
}
