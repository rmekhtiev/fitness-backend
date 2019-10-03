<?php

namespace App\Models;

use App\Models\Pivot\ClientGroup;
use App\Transformers\BaseTransformer;
use Spatie\QueryBuilder\AllowedFilter;

class Group extends BaseModel
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'group_id';

    /**
     * @var null|array What relations should one model of this entity be returned with, from a relevant controller
     */
    public static $itemWith = [];

    public static $itemWithCount = [
        'clients',
    ];

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
        'title',
        'hall_id',
        'max_members',
        'trainer_id',
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
            'title' => 'required',
            'hall_id' => 'required|uuid|exists:halls,hall_id',
            'max_members' => 'required|numeric|integer',
            'trainer_id' => 'sometimes|nullable|uuid|exists:trainers,trainer_id',

        ];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('group_id'),
            AllowedFilter::exact('id', 'group_id'),
            AllowedFilter::exact('hall_id'),
            AllowedFilter::exact('trainer_id'),
            AllowedFilter::partial('name'),
        ];
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id');
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_group', 'group_id', 'client_id')
            ->using(ClientGroup::class)
            ->withTimestamps();
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }


}
