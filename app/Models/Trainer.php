<?php

namespace App\Models;

use App\Transformers\BaseTransformer;

class Trainer extends BaseModel
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'trainer_id';

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
        'last_name',
        'middle_name',
        'phone_number',
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
    public function getValidationRules()
    {
        return [
            'phone_number' => 'max:255|unique:trainers',
            'name' => 'required|min:3|max:255',

            'first_name' => 'required',
            'middle_name' => 'sometimes|nullable',
            'last_name' => 'required',

            'hall_id' => 'sometimes|nullable|uuid|exists:halls,hall_id',
        ];
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id');
    }

    public function associatedEmployee()
    {
        return $this->belongsTo(Employee::class, 'associated_employee_id');
    }


}
