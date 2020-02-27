<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarItem\BarItemSellRequest;
use Illuminate\Http\Request;
use App\Models\BarItem;

class BarItemController extends Controller
{
    public static $model = BarItem::class;

    public static $parentModel = null;

    public static $transformer = null;

    public function sell($uuid, BarItemSellRequest $request)
    {
        /** @var BarItem $model */
        $model = static::$model::findOrFail($uuid);

        $this->authorizeUserAction('update', $model);

        $validated = $request->validated();

        $model->sell($validated['payment_method'], $validated['quantity']);

        if ($this->shouldTransform()) {
            $response = $this->response->item($model, $this->getTransformer());
        } else {
            $response = $model;
        }

        return $response;
    }
}
