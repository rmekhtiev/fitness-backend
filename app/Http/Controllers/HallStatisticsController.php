<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\Hall;
use App\Models\Payment;
use App\Transformers\HallStatisticsTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HallStatisticsController extends Controller
{
    /** @var string $model */
    public static $model = Hall::class;

    public static $parentModel = null;

    public static $transformer = HallStatisticsTransformer::class;

    public function get($uuid)
    {
        $model = new static::$model;

        /** @var BaseModel $model */
        $resource = $model::with($model::getItemWith())
            ->withCount($model::getItemWithCount())
            ->where($model->getKeyName(), '=', $uuid)
            ->with([
                'payments' => function($builder) {
                    /** @var Builder|Payment $builder */
                    return $builder->when(!empty($start = request('start')), function ($builder) use ($start) {
                        /** @var Builder|Payment $builder */
                        return $builder->start($start);
                    })->when(!empty($end = request('end')), function ($builder) use ($end) {
                        /** @var Builder|Payment $builder */
                        return $builder->end($end);
                    });
                }
            ])
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
