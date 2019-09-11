<?php

namespace App\Models;

use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Spatie\QueryBuilder\AllowedFilter;

class Locker extends BaseModel
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'locker_id';

    protected $perPage = null;

    /**
     * @var null|array What relations should one model of this entity be returned with, from a relevant controller
     */
    public static $itemWith = [
        'claim',
    ];

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
        'number',
        'hall_id',
    ];

    protected $appends = [
        'free',
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    public static function boot()
    {
        parent::boot();

        // Add functionality for creating a model
        static::creating(function (self $model) {

            if (empty($model->number)) {
                $model->number = (int)self::where('hall_id', $model->hall_id)->max('number') + 1;
            }
        });
    }

    public function scopeFree(Builder $query, $free = true)
    {
        return $query->when($free, function (Builder $query) {
            return $query->whereDoesntHave('claim');
        }, function (Builder $query) {
            return $query->whereHas('claim');
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
            'number' => 'sometimes|required|numeric', // todo: add unique within same hall

            'hall_id' => 'required|uuid|exists:halls,hall_id',
        ];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('id', 'locker_id'),
            AllowedFilter::exact('locker_id'),
            AllowedFilter::exact('hall_id'),
            AllowedFilter::exact('number'),
            AllowedFilter::scope('free'),
        ];
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id');
    }

    public function claims()
    {
        return $this->hasMany(LockerClaim::class, 'locker_id');
    }

    public function claim()
    {
        return $this->hasOne(LockerClaim::class, 'locker_id')
            ->whereDate('claim_start', '<=', Carbon::today())
            ->whereDate('claim_end', '>=', Carbon::today());
    }

    public function getFreeAttribute()
    {
        $this->loadMissing('claim');

        return empty($this->claim);
    }
}
