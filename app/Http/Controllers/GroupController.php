<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = Group::class;

    /**
     * @var BaseModel The parent model of the model, in the case of a child rest controller
     */
    public static $parentModel = null;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;

    public function events($uuid)
    {
        /** @var Group $model */
        $model = new static::$model;

        $resource = $model::with($model::getItemWith())
            ->withCount($model::getItemWithCount())
            ->where($model->getKeyName(), '=', $uuid)
            ->first();

        $fromDate = ($start_date = request()->query('start_date'))
            ? Carbon::parse($start_date)->subDay()
            : now();

        $toDate = ($end_date = request()->query('end_date'))
            ? Carbon::parse($end_date)->addDay()
            : now();

        $limit = request()->query('limit') ?? 200;

        $resources = $resource->getUpcomingEvents($fromDate, $toDate, $limit);

        return $this->response->collection($resources, $this->getTransformer());
    }
}
