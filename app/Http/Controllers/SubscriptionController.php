<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Http\Requests\SubscriptionSellRequest;

class SubscriptionController extends Controller
{

    public static $model = Subscription::class;

    public static $parentModel = null;

    public static $transformer = null;

    public function sell($uuid, SubscriptionSellRequest $request)
    {
        /** @var Subscription $model */
        $model = static::$model::findOrFail($uuid);

        $this->authorizeUserAction('update', $model);

        $validated = $request->validated();

        $model->sell($validated['payment_method']);

        if ($this->shouldTransform()) {
            $response = $this->response->item($model, $this->getTransformer());
        } else {
            $response = $model;
        }

        return $response;
    }
}
