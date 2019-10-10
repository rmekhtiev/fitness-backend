<?php

namespace App\Models;

use App\Transformers\BaseTransformer;
use Spatie\QueryBuilder\AllowedFilter;

class BarItem extends BaseModel
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'bar_item_id';

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
        'title',
        'amount'
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    public static $defaultSorts = 'amount';
    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     */
    public function getValidationRules()
    {
        return [
            'title' => 'required',
            'amount' => 'required|numeric',

        ];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::partial('title'),
            AllowedFilter::exact('hall_id'),
        ];
    }

}
