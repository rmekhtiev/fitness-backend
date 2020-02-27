<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    public static $model = Group::class;

    public static $parentModel = null;

    public static $transformer = null;

    public function events($uuid)
    {
        $modelInstance = new static::$model;

        /** @var Group $modelInstance */
        $resource = $modelInstance::with($modelInstance::getItemWith())
            ->withCount($modelInstance::getItemWithCount())
            ->where($modelInstance->getKeyName(), '=', $uuid)
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
