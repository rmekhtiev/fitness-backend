<?php


namespace App\Models;

use Plummer\Calendarful\Event\EventInterface;

/**
 * @property \DateTime start_date
 * @property \DateTime end_date
 */
abstract class EventModel extends BaseModel implements EventInterface
{
    /**
     * Get the unique id of the event.
     * Most likely a primary key of the record in a db etc.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->getKey();
    }

    /**
     * Get the id of the parent of the event.
     *
     * An event will tend to have a parent when it has overridden an occurrence of
     * the parent event that does recur.
     *
     * @return mixed
     */
    public function getParentId()
    {
        return null;
    }

    /**
     * Get the start date of the event.
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set the start date of the event.
     *
     * @param \DateTime $startDate
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->start_date = $startDate;
    }

    /**
     * Get the end date of the event.
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set the end date of the event.
     *
     * @param \DateTime $endDate
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->end_date = $endDate;
    }

    /**
     * Get the duration between the event start date and end date.
     *
     * @return \DateInterval
     */
    public function getDuration()
    {
        return $this->start_date->diff($this->end_date);
    }

    /**
     * Get the occurrence date of the event.
     *
     * When an occurrence of a recurring event is overridden, the date of that occurrence
     * should be the occurrence date property value of the new event that is created in its
     * place. When the start date of the parent recurring event is updated, the occurrence
     * date of the overriding event should also be updated.
     *
     * @return \DateTime
     */
    public function getOccurrenceDate()
    {
        return null; // TODO: Implement getOccurrenceDate() method.
    }
}
