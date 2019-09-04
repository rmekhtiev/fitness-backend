<?php

namespace App\Models;

use App\Transformers\BaseTransformer;

class LockerClaim extends BaseModel
{
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
        'locker_id',
        'client_id',

        'claim_start',
        'claim_end',
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
    public function getValidationRules()
    {
        return [
            'locker_id' => 'required|uuid|exists:lockers,locker_id',
            'client_id' => 'required|uuid|exists:clients,client_id',

            'claim_start' => 'required|date',
            'claim_end' => 'required|date|gte:claim_start',
        ];
    }

    public function locker()
    {
        return $this->belongsTo(Locker::class, 'locker_id');
    }

}
