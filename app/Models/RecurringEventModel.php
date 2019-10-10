<?php


namespace App\Models;


use Plummer\Calendarful\Event\RecurrentEventInterface;

/**
 * @property mixed recurrence_type
 * @property mixed recurrence_until
 */
abstract class RecurringEventModel extends EventModel implements RecurrentEventInterface
{

    protected $dates = [
//        'start_date',
//        'end_date',

        'recurrence_until',

        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d H:m',
        'end_date' => 'datetime:Y-m-d H:m',
    ];

    /**
     * Get the recurrence type of the event.
     *
     * This is usually a string that matches up to the label of a recurrence type.
     *
     * @return mixed
     */
    public function getRecurrenceType()
    {
        return $this->recurrence_type;
    }

    /**
     * Set the recurrence type of the event.
     *
     * @param string $type
     */
    public function setRecurrenceType($type = null)
    {
        if ($type === null) {
            $this->recurrence_until = null;
        }

        $this->recurrence_type = $type;
    }

    /**
     * Get the until date of the event.
     *
     * @return \DateTime
     */
    public function getRecurrenceUntil()
    {
        return $this->recurrence_until;
    }
}
