<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use Carbon\Traits\Boundaries;
use Illuminate\Http\Request;
use App\Models\Client;
use QrCode;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /** @var string $model */
    public static $model = Client::class;

    public static $parentModel = null;

    public static $transformer = null;



    public function avatar($uuid, Request $request) {

        $data = $request->avatar;

        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        $imageName = $uuid.'-'.rand(1,100).'.jpg';

        $client = Client::find($uuid);
        $delImg = str_replace('http://fitness.test/storage/', '', $client->avatar); //КОСТЫЛЬ

        Storage::disk('public')->delete($delImg);
        Storage::disk('public')->put($imageName, $data);
        $url = Storage::url($imageName);

        $client->avatar = $url;
        $client->save();
        return response($delImg);
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
