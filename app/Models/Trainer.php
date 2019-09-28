<?php

namespace App\Models;

use App\Transformers\BaseTransformer;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;

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
        'phone_number',
        'associated_employee_id'
    ];


    protected $appends = [
        'name',
        'full_name',
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
            'phone_number' => [
                'required',
                Rule::unique('trainers', 'phone_number')->ignoreModel($this),
            ],

            'associated_employee_id' => [
                'sometimes',
                'nullable',
                'uuid',
                'exists:employees,employee_id',
                Rule::unique('trainers', 'associated_employee_id')->ignoreModel($this),
            ],
        ];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('id', 'trainer_id'),
            AllowedFilter::exact('trainer_id'),
            AllowedFilter::exact('phone_number'),
            AllowedFilter::exact('associated_employee_id'),
        ];
    }


    public function associatedEmployee()
    {
        return $this->belongsTo(Employee::class, 'associated_employee_id');
    }

    /**
     * @return mixed|string
     */

    public function getHallIdAttribute()
    {
        // phpcs:ignore
        $associatedEmployee = $this->hasOne(Employee::class, 'employee_id', 'associated_employee_id')->first();
        return $associatedEmployee->hall_id;
    }

    public function getNameAttribute()
    {
        // phpcs:ignore
        $associatedEmployee = $this->hasOne(Employee::class, 'employee_id', 'associated_employee_id')->first();
        return $associatedEmployee->last_name ? $associatedEmployee->last_name . ($associatedEmployee->first_name ? (' ' . mb_substr($associatedEmployee->first_name, 0, 1) . '.') : '') . ($associatedEmployee->middle_name ? (' ' . mb_substr($associatedEmployee->middle_name, 0, 1) . '.') : '') : $associatedEmployee->first_name;
    }

    /**
     * @return mixed|string
     */
    public function getFullNameAttribute()
    {
        // phpcs:ignore
        $associatedEmployee = $this->hasOne(Employee::class, 'employee_id', 'associated_employee_id')->first();
        return $associatedEmployee->last_name ? $associatedEmployee->last_name . ($associatedEmployee->first_name ? (' ' . $associatedEmployee->first_name) : '') . ($associatedEmployee->middle_name ? (' ' . $associatedEmployee->middle_name) : '') : $associatedEmployee->first_name;
    }


}
