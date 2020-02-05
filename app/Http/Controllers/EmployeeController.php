<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public static $model = Employee::class;

    public static $parentModel = null;

    public static $transformer = null;
}
