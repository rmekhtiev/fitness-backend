<?php

namespace App\Models;

use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\QueryBuilder\AllowedFilter;

class LockerClaim extends BaseModel
{
    use LogsActivity;

    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'claim_id';

    protected $perPage = null;

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
        'locker_id',
        'client_id',

        'claim_start',
        'claim_end',
    ];

    protected $dates = [
        'claim_start',
        'claim_end',
    ];

    protected $appends = [
        'duration'
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    protected static $recordEvents = [
        'created'
    ];

    protected static $logName = 'events';

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->hall_id = $this->locker->hall_id;
    }

    public static function boot()
    {
        parent::boot();

        self::addGlobalScope('order', function (Builder $query) {
            return $query->orderByDesc('claim_end');
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
            'locker_id' => 'required|uuid|exists:lockers,locker_id',
            'client_id' => 'required|uuid|exists:clients,client_id',

            'claim_start' => 'required|date',
            'claim_end' => 'required|date|gte:claim_start',
        ];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('id', 'claim_id'),
            AllowedFilter::exact('claim_id'),
            AllowedFilter::exact('client_id'),
            AllowedFilter::exact('locker_id'),
            AllowedFilter::scope('active'),
            AllowedFilter::scope('after'),
            AllowedFilter::scope('before'),
        ];
    }

    public function scopeActive(Builder $query, $active = true)
    {
        return $query->when($active, function (Builder $query) {
            $query->whereDate('claim_start', '<=', Carbon::today())
                ->whereDate('claim_end', '>=', Carbon::today());
        }, function (Builder $query) {
            $query->whereDate('claim_start', '>=', Carbon::today())
                ->whereDate('claim_end', '<=', Carbon::today());
        });
    }

    /**
     * @param Builder $query
     * @param \DateTimeInterface|string|null $value
     */
    public function scopeAfter(Builder $query, $value)
    {
        $query->whereDate('claim_start', '>=', $value);
    }

    /**
     * @param Builder $query
     * @param \DateTimeInterface|string|null $value
     */
    public function scopeBefore(Builder $query, $value)
    {
        $query->whereDate('claim_end', '<=', $value);
    }

    public function locker()
    {
        return $this->belongsTo(Locker::class, 'locker_id');
    }

    public function getDurationAttribute()
    {
        return $this->claim_end->diffInDays($this->claim_start);
    }
}
