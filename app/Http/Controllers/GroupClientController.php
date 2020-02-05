<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\Client;
use App\Models\Group;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class GroupClientController extends Controller
{
    public static $model = Client::class;

    public static $parentModel = Group::class;

    public static $transformer = null;

    public function getAll()
    {
        $uuid = request()->segment(3); // todo: дикий костыль

        $this->authorizeUserAction('viewAll');

        /** @var BaseModel|Client $modelInstance */
        $modelInstance = new static::$model;

        /** @var BaseModel|Group $parentModelInstance */
        $parentModelInstance = new static::$parentModel;

        /** @var Group $resource */
        $resource = $parentModelInstance->where($parentModelInstance->getKeyName(), '=', $uuid)->firstOrFail();

        $query = $resource->clients()
            ->with($modelInstance::getCollectionWith())
            ->withCount($modelInstance::getCollectionWithCount());

        $this->qualifyCollectionQuery($query);

        // Handle pagination, if applicable
        $perPage = $modelInstance->getPerPage();
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

        /** @var BaseModel|Client $modelInstance */
        $modelInstance = new static::$model;

        /** @var BaseModel|Group $parentModelInstance */
        $parentModelInstance = new static::$parentModel;

        /** @var Group $group */
        $group = $parentModelInstance->where($parentModelInstance->getKeyName(), '=', $parentUuid)->firstOrFail();

        $this->authorizeUserAction('update', $group);

        $client = $modelInstance->findOrFail($uuid);

        $group->clients()->attach($client);

        return $this->response->collection($group->clients, $this->getTransformer());
    }

    public function delete($parentUuid) // phpcs:ignore PHPCS_SecurityAudit.BadFunctions.FilesystemFunctions
    {
        $args = func_get_args();

        $uuid = $args[1]; // todo: лучше, чем было, но все равно костыль

        /** @var BaseModel|Client $modelInstance */
        $modelInstance = new static::$model;

        /** @var BaseModel|Group $parentModelInstance */
        $parentModelInstance = new static::$parentModel;

        /** @var Group $group */
        $group = $parentModelInstance->where($parentModelInstance->getKeyName(), '=', $parentUuid)->firstOrFail();

        $this->authorizeUserAction('update', $group);

        $client = $modelInstance->findOrFail($uuid);

        $group->clients()->detach($client);

        return $this->response->collection($group->clients, $this->getTransformer());
    }
}
