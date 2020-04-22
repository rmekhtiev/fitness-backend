<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientController extends Controller
{
    /** @var string $model */
    public static $model = Client::class;

    public static $parentModel = null;

    public static $transformer = null;

    public function avatar($uuid, Request $request)
    {
        $modelInstance = new static::$model;

        /** @var BaseModel $modelInstance */
        $resource = $modelInstance::with($modelInstance::getItemWith())
            ->withCount($modelInstance::getItemWithCount())
            ->where($modelInstance->getKeyName(), '=', $uuid)
            ->first();

        if (!$resource) {
            throw new NotFoundHttpException(
                'Resource \'' . class_basename(static::$model) . '\' with given UUID ' . $uuid . ' not found'
            );
        }

        $this->authorizeUserAction('update', $resource);

        $resource->avatar = Storage::disk('avatars')->putFileAs(
            'clients', $request->file('avatar'), $uuid
        );
        $resource->save();

        return $this->response->item($resource, $this->getTransformer());
    }

    public function qrcode($uuid)
    {
        $modelInstance = new static::$model;

        /** @var BaseModel $modelInstance */
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
        $modelInstance = new static::$model;

        /** @var BaseModel $modelInstance */
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
