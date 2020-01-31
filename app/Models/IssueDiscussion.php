<?php

namespace App\Models;

use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;

class IssueDiscussion extends BaseModel
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'issue_discussion_id';

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
        'text',
        'user_id',
        'issue_id'
    ];

    protected $appends = [
        'user_name',
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

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderByTime', function (Builder $builder) {
            return $builder->orderBy('created_at', 'DESC');
        });
    }

    public function getValidationRules()
    {
        return [
            'text' => 'max:255',
        ];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('issue_id'),
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function issue()
    {
        return $this->belongsTo(Issue::class, 'issue_id');
    }

    public function getUserNameAttribute()
    {
        return $this->user->name;
    }
}
