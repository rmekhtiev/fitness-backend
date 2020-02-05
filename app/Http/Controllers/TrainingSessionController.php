<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionSellRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\TrainingSession;

class TrainingSessionController extends Controller
{

    public static $model = TrainingSession::class;

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
