<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\Client;
use App\Models\Group;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class GroupClientController extends Controller
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = Client::class;

    /**
     * @var BaseModel The parent model of the model, in the case of a child rest controller
     */
    public static $parentModel = Group::class;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
    public static $transformer = null;

    public function getAll()
    {
        $uuid = request()->segment(3); // todo: дикий костыль

        $this->authorizeUserAction('viewAll');

        /** @var BaseModel|Client $model */
        $model = new static::$model;

        /** @var BaseModel|Group $parentModel */
        $parentModel = new static::$parentModel;

        /** @var Group $resource */
        $resource = $parentModel->where($parentModel->getKeyName(), '=', $uuid)->firstOrFail();

        $query = $resource->clients()->with($model::getCollectionWith())->withCount($model::getCollectionWithCount());

        $this->qualifyCollectionQuery($query);

        // Handle pagination, if applicable
        $perPage = $model->getPerPage();
        if ($perPage) {
            $paginator = $query->paginate($perPage);

            return $this->response->paginator($paginator, $this->getTransformer());
        } else {
            $resources = $query->get();

            return $this->response->collection($resources, $this->getTransformer());
        }
    }

    public function put(Request $request, $parentUuid)
    {
        $args = func_get_args();

        $uuid = $args[2]; // todo: лучше, чем было, но все равно костыль

        /** @var BaseModel|Client $model */
        $model = new static::$model;

        /** @var BaseModel|Group $parentModel */
        $parentModel = new static::$parentModel;

        /** @var Group $group */
        $group = $parentModel->where($parentModel->getKeyName(), '=', $parentUuid)->firstOrFail();

        $this->authorizeUserAction('update', $group);

        $client = $model->findOrFail($uuid);

        $group->clients()->attach($client);

        return $this->response->collection($group->clients, $this->getTransformer());
        }
    }
}
