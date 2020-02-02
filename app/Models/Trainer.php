<?php

namespace App\Models;

use App\Plummer\Calendarful\Calendar\AccessibleCalendar;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Plummer\Calendarful\Event\EventRegistryInterface;
use Plummer\Calendarful\Recurrence\RecurrenceFactory;
use Plummer\Calendarful\Recurrence\Type\Daily;
use Plummer\Calendarful\Recurrence\Type\MonthlyDate;
use Plummer\Calendarful\Recurrence\Type\Weekly;
use Spatie\QueryBuilder\AllowedFilter;

class Trainer extends BaseModel implements EventRegistryInterface
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'trainer_id';

    /**
     * @var null|array What relations should one model of this entity be returned with, from a relevant controller
     */
    public static $itemWith = [
        'events',
    ];

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
            AllowedFilter::scope('hall_id', 'whereHallId'),
            AllowedFilter::scope('search')
        ];
    }

    public function scopeWhereHallId(Builder $builder, $hall_id)
    {
        return $builder->whereHas('associatedEmployee', function (Builder $builder) use ($hall_id) {
            return $builder->where('hall_id', $hall_id);
        });
    }

    public function scopeSearch(Builder $query, $search)
    {
        return $query->whereHas('associatedEmployee', function (Builder $query) use ($search) {
            return $query->where('first_name', 'ILIKE', "%{$search}%")
                ->orWhere('middle_name', 'ILIKE', "%{$search}%")
                ->orWhere('last_name', 'ILIKE', "%{$search}%");
        });
    }

    public function associatedEmployee()
    {
        return $this->belongsTo(Employee::class, 'associated_employee_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function sessions()
    {
        return $this->belongsTo(TrainingSession::class, 'trainer_id', 'trainer_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'trainer_id');
    }

    public function events()
    {
        return $this->schedules()->whereNull('recurrence_type');
    }

    public function recurringEvents()
    {
        return $this->schedules()->whereNotNull('recurrence_type');
    }

    public function getEvents(array $filters = array())
    {
        $this->loadMissing('events');

        return $this->events->all();
    }

    public function getRecurrentEvents(array $filters = array())
    {
        $this->loadMissing('recurringEvents');

        return $this->recurringEvents->all();
    }

    public function getUpcomingEvents(
        \DateTime $fromDate,
        \DateTime $toDate,
        $limit = 200,
        array $extraFilters = array()
    ) {
        $recurrenceFactory = new RecurrenceFactory(); // todo
        $recurrenceFactory->addRecurrenceType('daily', Daily::class);
        $recurrenceFactory->addRecurrenceType('weekly', Weekly::class);
        $recurrenceFactory->addRecurrenceType('monthly', MonthlyDate::class);

        $calendar = new AccessibleCalendar($recurrenceFactory);
        $calendar->populate($this, $fromDate, $toDate, $limit, $extraFilters);
        $calendar->sort();

        return collect($calendar->getAllItems());
    }

    /**
     * @return mixed|string
     */
    public function getHallIdAttribute()
    {
        // phpcs:ignore
        return $this->associatedEmployee->hall_id;
    }

    /**
     * @return mixed|string
     */
    public function getNameAttribute()
    {
        $associatedEmployee = $this->associatedEmployee;

        // phpcs:ignore
        return $associatedEmployee->last_name ? $associatedEmployee->last_name . ($associatedEmployee->first_name ? (' ' . mb_substr($associatedEmployee->first_name, 0, 1) . '.') : '') . ($associatedEmployee->middle_name ? (' ' . mb_substr($associatedEmployee->middle_name, 0, 1) . '.') : '') : $associatedEmployee->first_name;
    }

    /**
     * @return mixed|string
     */
    public function getFullNameAttribute()
    {
        $associatedEmployee = $this->associatedEmployee;

        // phpcs:ignore
        return $associatedEmployee->last_name ? $associatedEmployee->last_name . ($associatedEmployee->first_name ? (' ' . $associatedEmployee->first_name) : '') . ($associatedEmployee->middle_name ? (' ' . $associatedEmployee->middle_name) : '') : $associatedEmployee->first_name;
    }
}
