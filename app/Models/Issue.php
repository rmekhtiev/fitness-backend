<?php

namespace App\Models;

use App\Enums\IssueType;
use App\Transformers\BaseTransformer;
use BenSampo\Enum\Rules\EnumValue;

class Issue extends BaseModel
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'issue_id';

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
        'description',
        'status',
        'hall_id',
        'user_id',
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
            'description' => 'required|max:255',
            'status' => ['required', new EnumValue(IssueType::class)],

            'hall_id' => 'required|nullable|uuid|exists:halls,hall_id',
            'user_id' => 'required|nullable|uuid|exists:users,user_id',
        ];
    }


    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
