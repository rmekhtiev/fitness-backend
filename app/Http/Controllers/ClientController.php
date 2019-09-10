<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use Carbon\Traits\Boundaries;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = Client::class;

    /**
     * @var BaseModel The parent model of the model, in the case of a child rest controller
     */
    public static $parentModel = null;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;

    public function getAll()
    {
        $clients = Client::with('latestSubscription')->get()->sortByDesc('latestSubscription.valid_till');
        $futureSubscription = Client::with('latestSubscription')->get()->whereDate('valid_till', '>', Carbon::now());
        $pastSubscription = Client::with('latestSubscription')->get()->whereDate('valid_till', '<', Carbon::now());
        $clients = $futureSubscription-> merge($pastSubscription);
        return new ClientsResourceCollection(ClientsResource::collection($clients));
    }
}
