<?php

namespace App\Models;

use App\Transformers\BaseTransformer;

class Client extends BaseModel
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'client_id';

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
        'first_name',
        'middle_name',
        'last_name',
        'phone_number',
        'email',
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     * @todo: regexp for phone
     *
     */
    public function getValidationRules()
    {
        return [
            'first_name' => 'required',
            'second_name' => 'sometimes|nullable',
            'last_name' => 'required',
            'phone_number' => 'required|unique:clients,phone_number',
            'email' => 'sometimes|nullable|email|unique:clients,phone_number',
        ];
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id');
    }

}
