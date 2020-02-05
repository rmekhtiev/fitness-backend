<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\QueryBuilder\QueryBuilder;
use Specialtactics\L5Api\Http\Controllers\RestfulController as BaseController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Specify the model that you want to be associated with this controller. This is the primary model that
     * the controller deals with
     *
     * @var string $model
     */
    public static $model = null;

    /**
     * Request to retrieve a collection of all items of this resource
     *
     * @return \Dingo\Api\Http\Response
     */
    public function getAll()
    {
        $this->authorizeUserAction('viewAll');

        $modelInstance = new static::$model;

        /** @var BaseModel $modelInstance */
        $query = $modelInstance::with($modelInstance::getCollectionWith())
            ->withCount($modelInstance::getCollectionWithCount());

        $this->qualifyCollectionQuery($query);

        $query = QueryBuilder::for($query)
            ->defaultSort($modelInstance::getDefaultSorts())
            ->allowedSorts($modelInstance::getAllowedSorts())
            ->allowedAppends($modelInstance::getAllowedAppends())
            ->allowedFields($modelInstance::getAllowedFields())
            ->allowedIncludes($modelInstance::getAllowedIncludes())
            ->allowedFilters($modelInstance::getAllowedFilters());

        // Handle pagination, if applicable
        $perPage = request('per_page') ?? $modelInstance->getPerPage();
        if ($perPage) {
            $paginator = $query->paginate($perPage);

            return $this->response->paginator($paginator, $this->getTransformer());
        } else {
            $resources = $query->get();

            return $this->response->collection($resources, $this->getTransformer());
        }
    }

    /**
     * Request to retrieve a single item of this resource
     *
     * @param string $uuid UUID of the resource
     * @return \Dingo\Api\Http\Response
     * @throws HttpException
     */
    public function get($uuid)
    {
        $model = new static::$model;

        /** @var BaseModel $model */
        $resource = $model::with($model::getItemWith())
            ->withCount($model::getItemWithCount())
            ->where($model->getKeyName(), '=', $uuid)
            ->first();

        if (! $resource) {
            throw new NotFoundHttpException(
                'Resource \'' . class_basename(static::$model) . '\' with given UUID ' . $uuid . ' not found'
            );
        }

        $this->authorizeUserAction('view', $resource);

        return $this->response->item($resource, $this->getTransformer());
    }
}
