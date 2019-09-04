<?php

namespace App\Models;

use App\Transformers\BaseTransformer;

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
        'client_id',
        'issue_date',
        'valid_till',
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
            'subscription_id' => 'required|uuid|exists:lockers,locker_id',
            'client_id' => 'required|uuid|exists:clients,client_id',

            'issue_date' => 'required|date',
            'valid_till' => 'required|date',
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
