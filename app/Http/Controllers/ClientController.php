<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use Carbon\Traits\Boundaries;
use Illuminate\Http\Request;
use App\Models\Client;
use QrCode;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function qrcode($uuid)
    {
        /** @var BaseModel $model */
        $model = new static::$model;

        $resource = $model::with($model::getItemWith())->withCount($model::getItemWithCount())->where($model->getKeyName(), '=', $uuid)->first();

        if (!$resource) {
            throw new NotFoundHttpException('Resource \'' . class_basename(static::$model) . '\' with given UUID ' . $uuid . ' not found');
        }

        $this->authorizeUserAction('view', $resource);

        return response($resource->getQrCode())->header("Content-Type", 'image/png');
    }

    public function printCard($uuid)
    {
        /** @var BaseModel $model */
        $model = new static::$model;

        $resource = $model::with($model::getItemWith())->withCount($model::getItemWithCount())->where($model->getKeyName(), '=', $uuid)->first();

        if (!$resource) {
            throw new NotFoundHttpException('Resource \'' . class_basename(static::$model) . '\' with given UUID ' . $uuid . ' not found');
        }

//        $this->authorizeUserAction('view', $resource);

        return view('clients.print', ['client' => $resource]);
    }
}
