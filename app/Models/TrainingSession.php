<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Builder;
use Plummer\Calendarful\Event\EventRegistryInterface;
use Spatie\QueryBuilder\AllowedFilter;

class TrainingSession extends BaseModel implements EventRegistryInterface
{
    use Sellable, Schedulable;

    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'training_session_id';

    /**
     * @var null|array What relations should one model of this entity be returned with, from a relevant controller
     */
    public static $itemWith = [
        'pastEvents'
    ];

    /**
     * @var null|array What relations should a collection of models of this entity be returned with, from a relevant controller
     * If left null, then $itemWith will be used
     */
    public static $collectionWith = null;

    public static $itemWithCount = [
        'events',
    ];

    /**
     * @var null|BaseTransformer The transformer to use for this model, if overriding the default
     */
    public static $transformer = null;

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'client_id',
        'trainer_id',
        'cost',
        'date_start',
        'date_end',
        'comment',
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    protected $appends = [
        'sold',
        'pastEventsCount',
        'title',
    ];

    public static $allowedSorts = [
        'cost',
        'count',
    ];

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     */
    public function getValidationRules()
    {
        return [
            'client_id' => 'required|nullable|uuid|exists:clients,client_id',
            'trainer_id' => 'required|nullable|uuid|exists:trainers,trainer_id',
            'cost' => 'required', // todo
            'date_start' => 'required', // todo
            'date_end' => 'required', // todo
            'comment' => 'max:255'
        ];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('training_session_id'),
            AllowedFilter::exact('id', 'training_session_id'),
            AllowedFilter::exact('client_id'),
            AllowedFilter::exact('trainer_id'),
            AllowedFilter::exact('cost'),

            AllowedFilter::scope('after'),
            AllowedFilter::scope('before'),
            AllowedFilter::scope('sold'),
            AllowedFilter::scope('active'),
            AllowedFilter::scope('hall_id'),
        ];
    }

    /**
     * @param Builder $query
     * @param \DateTimeInterface|string|null $value
     */
    public function scopeAfter(Builder $query, $value)
    {
        $query->whereDate('date_start', '<=', $value);
    }

    /**
     * @param Builder $query
     * @param \DateTimeInterface|string|null $value
     */
    public function scopeBefore(Builder $query, $value)
    {
        $query->whereDate('date_end', '>=', $value);
    }

    /**
     * @param Builder|self $builder
     * @param $flag
     * @return mixed
     */
    public function scopeActive(Builder $builder, $flag = true)
    {
        return $builder->when($flag, function (Builder $builder, $flag) {
            return $builder->whereDay('date_end', '>=', now());
        }, function (Builder $builder, $flag) {
            return $builder->whereDay('date_end', '<=', now());
        });
    }

    public function scopeHallId(Builder $builder, $hall_id)
    {
        return $builder->whereHas('trainer', function (Builder $builder) use ($hall_id) {
            return $builder->whereHas('associatedEmployee', function (Builder $builder) use ($hall_id){
                return $builder->where('hall_id', $hall_id);
            });
        });
    }

    /**
     * @param Builder|self $builder
     * @param $flag
     * @return mixed
     */
    public function scopeSold(Builder $builder, $flag = true)
    {
        return $builder->when($flag, function (Builder $builder) {
            return $builder->whereHas('payment');
        }, function (Builder $builder) {
            return $builder->whereDoesntHave('payment');
        });
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'client_id', 'client_id');
    }

    public function trainer()
    {
        return $this->hasOne(Trainer::class, 'trainer_id', 'trainer_id');
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'sellable');
    }

    /**
     * @return Builder
     */
    public function pastEvents()
    {
        return $this->events()->whereDate('end_date', '<=', now());
    }

    public function getSoldAttribute()
    {
        return $this->payment != null;
    }

    public function getPastEventsCountAttribute()
    {
        return $this->pastEvents()->count();
    }

    public function getTitleAttribute()
    {
        return $this->client->name;
    }

    public function sell($paymentMethod = PaymentMethod::CASH)
    {
        $payment = $this->payment()->create([
            'cost' => $this->cost,
            'quantity' => 1,
            'method' => $paymentMethod,
            'hall_id' => $this->trainer->associatedEmployee->hall_id,
        ]);

        if ($this->update()) {
            return $payment->resolve();
        } else {
            $payment->fail();
        }
        return false;
    }
}
