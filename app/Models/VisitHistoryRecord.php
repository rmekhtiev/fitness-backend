<?php

namespace App\Models;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;

class VisitHistoryRecord extends BaseModel
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'record_id';

    /**
     * @var null|array What relations should one model of this entity be returned with, from a relevant controller
     */
    public static $itemWith = [];

    /**
     * @var null|array What relations should a collection of models of this entity be returned with,
     * from a relevant controller
     *
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
        'client_id',
        'hall_id',
        'datetime',
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];


    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderByTime', function (Builder $builder) {
            return $builder->orderBy('datetime', 'DESC');
        });
    }

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     */
    public function getValidationRules()
    {
        return [
            'datetime' => 'required|date',
            'hall_id' => 'nullable',
            'client_id' => 'required|uuid|exists:clients,client_id',
        ];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('id', 'record_id'),
            AllowedFilter::exact('client_id', 'client_id'),
            AllowedFilter::scope('start'),
            AllowedFilter::scope('end'),
            AllowedFilter::scope('hall'),
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

    public function scopeHall(Builder $builder, $hall_id)
    {
        return $builder->whereHas('client', function (Builder $builder) use ($hall_id) {
            return $builder->where('primary_hall_id', $hall_id);
        });
    }


    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
