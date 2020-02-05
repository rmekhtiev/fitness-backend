<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requests;

class RequestsController extends Controller
{

    public static $model = Requests::class;

    public static $parentModel = null;

    public static $transformer = null;

    public function store(Request $request)
    {
        $kek = new Requests();
        $kek->name = $request[0][env('FORM_NAME')];
        $kek->phone = $request[1][env('FORM_PHONE')];
        $kek->save();
    }
}
