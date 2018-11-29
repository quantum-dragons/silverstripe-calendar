<?php

namespace QuantumDragons\Calendar;

interface CalendarProvider
{
    /**
     * @param $year
     * @param $month
     * @param $day
     *
     * @return mixed
     */
    public function getCalendarItems($year, $month, $day);
}
