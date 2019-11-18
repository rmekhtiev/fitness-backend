<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;

class Payment extends BaseModel
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'payment_id';

    /**
     * @var null|array What relations should one model of this entity be returned with, from a relevant controller
     */
    public static $itemWith = [];

    /**
     * @var null|array What relations should a collection of models of this entity be returned with, from a relevant controller
     * If left null, then $itemWith will be used
     */
    public static $collectionWith = null;

    /**
     * @var null|BaseTransformer The transformer to use for this model, if overriding the default
     */
    public static $transformer = null;

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'cost',
        'quantity',
        'discount',
        'method',
        'hall_id',
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     */
    protected $appends = [];

    public function getValidationRules()
    {
        return [];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('sellable_type'),
            AllowedFilter::exact('sellable_id'),
            AllowedFilter::exact('payment_id'),
            AllowedFilter::exact('method'),
            AllowedFilter::scope('start'),
            AllowedFilter::scope('end'),
            AllowedFilter::exact('hall_id'),
        ];
    }


    /**
     * @param Builder $query
     * @param \DateTimeInterface|string|null $value
     */
    public function scopeStart(Builder $query, $value)
    {
        $query->whereDate('created_at', '>=', $value);
    }

    /**
     * @param Builder $query
     * @param \DateTimeInterface|string|null $value
     */
    public function scopeEnd(Builder $query, $value)
    {
        $query->whereDate('created_at', '<=', $value);
    }

    /**
     * Get the owning imageable model.
     */
    public function sellable()
    {
        return $this->morphTo();
    }

    public function resolve($status = PaymentStatus::SUCCESS)
    {
        $this->status = $status;

        return $this->update();
    }

    public function fail()
    {
        return $this->resolve(PaymentStatus::FAILED);
    }
}
