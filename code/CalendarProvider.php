<?php

interface CalendarProvider
{
    public function getCalendarItems($year, $month, $day);
}
