<?php


namespace App\Plummer\Calendarful\Calendar;


use Plummer\Calendarful\Calendar\Calendar;

class AccessibleCalendar extends Calendar
{
    public function getAllItems()
    {
        return $this->allEvents;
    }
}
