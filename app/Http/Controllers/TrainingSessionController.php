<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionSellRequest;
use Illuminate\Http\Request;
use App\Models\TrainingSession;

class TrainingSessionController extends Controller
{
    /**
     * @var BaseModel The primary model associated with this controller
     */
    public static $model = TrainingSession::class;

    /**
     * @var BaseModel The parent model of the model, in the case of a child rest controller
     */
    public static $parentModel = null;

    /**
     * @var null|BaseTransformer The transformer this controller should use, if overriding the model & default
     */
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
