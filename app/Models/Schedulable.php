<?php


namespace App\Models;


use App\Plummer\Calendarful\Calendar\AccessibleCalendar;
use Plummer\Calendarful\Event\EventInterface;
use Plummer\Calendarful\Recurrence\RecurrenceFactory;
use Plummer\Calendarful\Recurrence\Type\Daily;
use Plummer\Calendarful\Recurrence\Type\MonthlyDate;
use Plummer\Calendarful\Recurrence\Type\Weekly;

/**
 * Trait Schedulable
 * @package App\Models
 * @mixin BaseModel
 */
trait Schedulable
{
    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'schedulable');
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
        $this->loadMissing('schedules');

        return $this->schedules->all();
    }

    public function getUpcomingEvents(\DateTime $fromDate, \DateTime $toDate, $limit = 200, array $extraFilters = array())
    {
        $recurrenceFactory = new RecurrenceFactory(); // todo
        $recurrenceFactory->addRecurrenceType('daily', Daily::class);
        $recurrenceFactory->addRecurrenceType('weekly', Weekly::class);
        $recurrenceFactory->addRecurrenceType('monthly', MonthlyDate::class);

        $calendar = new AccessibleCalendar($recurrenceFactory);
        $calendar->populate($this, $fromDate, $toDate, $limit, $extraFilters);
        $calendar->sort();

        return collect($calendar->getAllItems());
    }

    public function getUpcomingEventsAttribute()
    {
        return $this->getUpcomingEvents(now(), now()->addMonth(2));
    }
}
