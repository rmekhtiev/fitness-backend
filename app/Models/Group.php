<?php

namespace App\Models;

use App\Models\Pivot\ClientGroup;
use App\Plummer\Calendarful\Calendar\AccessibleCalendar;
use App\Transformers\BaseTransformer;
use Plummer\Calendarful\Calendar\Calendar;
use Plummer\Calendarful\Event\EventInterface;
use Plummer\Calendarful\Event\EventRegistryInterface;
use Plummer\Calendarful\Recurrence\RecurrenceFactory;
use Plummer\Calendarful\Recurrence\Type\Daily;
use Plummer\Calendarful\Recurrence\Type\MonthlyDate;
use Plummer\Calendarful\Recurrence\Type\Weekly;
use Spatie\QueryBuilder\AllowedFilter;

class Group extends BaseModel implements EventRegistryInterface
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

    protected $appends = [
        'upcomingEvents',
    ];

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

    public function recurs()
    {
        return $this->hasMany(GroupRecur::class, 'group_id', 'group_id');
    }

    /**
     * Gets data and allows the passing of filters if desired.
     *
     * @param array $filters
     * @return    EventInterface[]
     */
    public function getEvents(array $filters = array())
    {
        return [];
    }

    /**
     * Gets data and allows the passing of filters if desired.
     *
     * @param array $filters
     * @return    EventInterface[]
     */
    public function getRecurrentEvents(array $filters = array())
    {
        $this->loadMissing('recurs');

        return $this->recurs->all();
    }

    public function getUpcomingEvents(\DateTime $fromDate, \DateTime $toDate, $limit = 200, array $extraFilters = array())
    {
        $recurrenceFactory = new RecurrenceFactory(); // todo
        $recurrenceFactory->addRecurrenceType('daily', Daily::class);
        $recurrenceFactory->addRecurrenceType('weekly', Weekly::class);
        $recurrenceFactory->addRecurrenceType('monthly', MonthlyDate::class);

        $calendar = new AccessibleCalendar($recurrenceFactory);
        $calendar->populate($this, $fromDate, $toDate, $limit, $extraFilters);

        return collect($calendar->getAllItems());
    }

    public function getUpcomingEventsAttribute()
    {
        return $this->getUpcomingEvents(now(), now()->addMonth(2));
    }
}
