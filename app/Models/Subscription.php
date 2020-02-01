<?php

namespace App\Models;

use App\Enums\ClientStatus;
use App\Enums\PaymentMethod;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;

class Subscription extends BaseModel
{

    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'subscription_id';

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
        'issue_date',
        'valid_till',
        'frozen_till',
        'frozen_start',
        'subscriable_id',
        'subscriable_type',
        'cost',
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    protected $appends = [
        'sold'
    ];

    public static function boot()
    {
        parent::boot();
//        self::created(function (self $subscription){
//            $group = $this->client()->groups()->create([
//                'cost' => $this->cost,
//                'quantity' => 1,
//                'method' => $paymentMethod,
//            ]);
//
//            if ($this->update())
//            {
//                return $payment->resolve();
//            } else {
//                $payment->fail();
//            }
//            return false;
//        });
    }

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     */
    public function getValidationRules()
    {
        return [
            'client_id' => 'required|uuid|exists:clients,client_id',
            'frozen_start' => 'date|nullable',
            'frozen_till' => 'date|nullable',
            'issue_date' => 'required|date',
            'valid_till' => 'required|date',
            'subscriable_id' => 'sometimes|nullable|uuid',
        ];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('subscription_id'),
            AllowedFilter::exact('client_id'),
            AllowedFilter::exact('id', 'subscription_id'),
        ];
    }

    public function scopeFrozen(Builder $query)
    {
        return $query->where('frozen_till', '>=', today());
    }

    public function scopeSubscriable(Builder $query, $search)
    {
        return $query->where('subscriable_id', "=", $search);
    }

    public function scopeInactive(Builder $query)
    {
        return $query->where('issue_date', '>', today());
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('issue_date', '<', today())
            ->where('valid_till', '>', today())
            ->where('frozen_till', '<=', today())
            ->orWhereNull('frozen_till');
    }

    public function scopeExpired(Builder $query)
    {
        return $query->where('valid_till', '<', today());
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function subscriable()
    {
        return $this->morphTo();
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'sellable');
    }

    public function getSoldAttribute()
    {
        return $this->payment != null;
    }

    public function getHallIdAttribute()
    {
        $this->loadMissing('client');

        return $this->client->primary_hall_id; // todo: КОСТЫЛЬ ССУКА
    }

    public function sell($paymentMethod = PaymentMethod::CASH)
    {
        $payment = $this->payment()->create([
            'cost' => $this->cost,
            'quantity' => 1,
            'method' => $paymentMethod,
            'hall_id' => optional($this->subscriable)->hall_id
                ?? optional(auth()->user()->associatedEmployee)->hall_id
                ?? $this->client->primary_hall_id
        ]);
        var_dump($this->subscriable);

        if ($this->update()) {
            return $payment->resolve();
        } else {
            $payment->fail();
        }
        return false;
    }
}
