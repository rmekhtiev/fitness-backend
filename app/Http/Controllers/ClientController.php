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
    /** @var string $model */
    public static $model = Client::class;

    public static $parentModel = null;

    public static $transformer = null;

    public function qrcode($uuid)
    {
        /** @var BaseModel $modelInstance */
        $modelInstance = new static::$model;

        $resource = $modelInstance::with($modelInstance::getItemWith())
            ->withCount($modelInstance::getItemWithCount())
            ->where($modelInstance->getKeyName(), '=', $uuid)
            ->first();

        if (!$resource) {
            throw new NotFoundHttpException(
                'Resource \'' . class_basename(static::$model) . '\' with given UUID ' . $uuid . ' not found'
            );
        }

        $this->authorizeUserAction('view', $resource);

        return response($resource->getQrCode())->header("Content-Type", 'image/png');
    }

    public function printCard($uuid)
    {
        /** @var BaseModel $modelInstance */
        $modelInstance = new static::$model;

        $resource = $modelInstance::with($modelInstance::getItemWith())
            ->withCount($modelInstance::getItemWithCount())
            ->where($modelInstance->getKeyName(), '=', $uuid)
            ->first();

        if (!$resource) {
            throw new NotFoundHttpException(
                'Resource \'' . class_basename(static::$model) . '\' with given UUID ' . $uuid . ' not found'
            );
        }

//        $this->authorizeUserAction('view', $resource);

        return view('clients.print', ['client' => $resource]);
    }
}
