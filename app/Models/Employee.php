<?php

namespace App\Models;

use App\Transformers\BaseTransformer;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\SoftDeletes; //add this line

class Employee extends BaseModel
{

    use SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['associatedTrainer'];

    protected $dates = ['deleted_at'];

    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'employee_id';

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
        'first_name',
        'middle_name',
        'last_name',

        'hall_id',
        'associated_user_id',
    ];

    protected $appends = [
        'name',
        'full_name',
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
            'first_name' => 'required',
            'second_name' => 'sometimes|nullable',
            'last_name' => 'required',

            'hall_id' => 'required|uuid|exists:halls,hall_id',
            'associated_user_id' => 'sometimes|nullable|uuid|exists:users,user_id'
        ];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('id', 'employee_id'),
            AllowedFilter::exact('employee_id'),
            AllowedFilter::exact('hall_id'),
            AllowedFilter::scope('trainer'),
            AllowedFilter::scope('search'),
            AllowedFilter::scope('user'),
        ];
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id');
    }

    public function associatedUser()
    {
        return $this->hasOne(User::class, 'employee_id');
    }

    public function associatedTrainer()
    {
        return $this->hasOne(Trainer::class, 'associated_employee_id');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'employee_id');
    }

    /**
     * @return mixed|string
     */
    public function getNameAttribute()
    {
        // phpcs:ignore
        return $this->last_name ? $this->last_name . ($this->first_name ? (' ' . mb_substr($this->first_name, 0, 1) . '.') : '') . ($this->middle_name ? (' ' . mb_substr($this->middle_name, 0, 1) . '.') : '') : $this->first_name;
    }

    /**
     * @return mixed|string
     */
    public function getFullNameAttribute()
    {
        // phpcs:ignore
        return $this->last_name ? $this->last_name . ($this->first_name ? (' ' . $this->first_name) : '') . ($this->middle_name ? (' ' . $this->middle_name) : '') : $this->first_name;
    }

    public function scopeTrainer(Builder $builder, $has)
    {
        return $has
            ? $builder->whereHas('associatedTrainer')
            : $builder->whereDoesntHave('associatedTrainer');
    }

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where('first_name', 'ILIKE', "%{$search}%")
            ->orWhere('middle_name', 'ILIKE', "%{$search}%")
            ->orWhere('last_name', 'ILIKE', "%{$search}%");
    }
    public function scopeUser(Builder $builder, $has)
    {
        return $has ? $builder->whereHas('associatedUser') : $builder->whereDoesntHave('associatedUser');
    }
}
