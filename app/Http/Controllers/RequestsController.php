<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requests;

class RequestsController extends Controller
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = Requests::class;

    /**
     * @var BaseModel The parent model of the model, in the case of a child rest controller
     */
    public static $parentModel = null;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;

    public function store( Request $request )
    {
        $kek = new Requests();
        $kek->name = $request[0][config('app.form.name')];
        $kek->phone = $request[1][config('app.form.phone')];
        $kek->save();
    }
}
