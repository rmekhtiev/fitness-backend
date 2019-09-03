<?php

namespace App\Models;

use App\Transformers\BaseTransformer;
use Carbon\Carbon;

class Hall extends BaseModel
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'hall_id';

    protected $perPage = null;

    /**
     * @var null|array What relations should one model of this entity be returned with, from a relevant controller
     */
    public static $itemWith = [];

    /**
     * @var null|array What relations should a collection of models of this entity be returned with, from a relevant controller
     * If left null, then $itemWith will be used
     */
    public static $collectionWith = null;

    public static $itemWithCount = [
        'clients',
        'employees',
        'lockers',
        'issues',
        'visit-histories',
    ];

    /**
     * @var null|BaseTransformer The transformer to use for this model, if overriding the default
     */
    public static $transformer = null;

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'address',
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    protected $appends = [
        'lockers_count_free',
        'clients_count_new',
    ];

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     * @todo
     *
     */
    public function getValidationRules()
    {
        return [
            'title' => 'required',
            'address' => 'required',
        ];
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'primary_hall_id');
    }

    public function visitHistories()
    {
        return $this->hasMany( VisitHistory::class, 'hall_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'hall_id');
    }

    public function lockers()
    {
        return $this->hasMany(Locker::class, 'hall_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'hall_id');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'hall_id');
    }

    public function getLockersCountFreeAttribute()
    {
        $this->loadMissing('lockers');

        return $this->lockers->filter(function (Locker $locker) {
            return $locker->free;
        })->count();
    }

    public function getClientsCountNewAttribute()
    {
        if(!$this->relationLoaded('clients')) {
            return $this->clients()->whereDate('created_at', '>=', now()->subMonth())->count();
        } else {
            return $this->clients->filter(function (Client $client) {
                return $client->created_at->isAfter(now()->subMonth());
            })->count();
        }
    }
}
