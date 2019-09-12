<?php

namespace App\Models;

use App\Enums\IssueType;
use App\Transformers\BaseTransformer;
use BenSampo\Enum\Rules\EnumValue;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\QueryBuilder\AllowedFilter;

class Issue extends BaseModel
{
    use LogsActivity;

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
        'topic',
        'description',
        'status',
        'hall_id',
        'user_id',
        'employee_id'
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    protected static $recordEvents = [
        'created'
    ];

    protected static $logName = 'events';

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->hall_id = $this->hall_id;
    }


    public static function boot()
    {
        parent::boot();

        self::creating(function(self $issue) {
            $issue->user()->associate(auth()->user()) ;
        });
    }

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     */
    public function getValidationRules()
    {
        return [
            'description' => 'required',
            'topic' => 'required|string|max:255',
            'status' => ['required', new EnumValue(IssueType::class)],

            'hall_id' => 'required|nullable|uuid|exists:halls,hall_id',
            'employee_id' => 'required|nullable|uuid|exists:employees,employee_id',
        ];
    }

    public static function getAllowedFilters()
    {
        return [
          AllowedFilter::exact('id', 'issue_id'),
          AllowedFilter::exact('issue_id'),
          AllowedFilter::exact('status'),
          AllowedFilter::partial('topic'),
          AllowedFilter::exact('employee_id'),
          AllowedFilter::exact('user_id'),
          AllowedFilter::exact('hall_id'),
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

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
