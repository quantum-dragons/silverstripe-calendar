<?php

class Calendar extends RequestHandler
{

    public function __construct($controller, $name, $calendarProvider)
    {
        parent::__construct();

        $this->controller = $controller;
        $this->name = $name;
        $this->calendarProvider = $calendarProvider;

        $date = isset($_GET['calendardate']) ? strtotime($_GET['calendardate']) : time();

        $this->year = (int)date('Y', $date);
        $this->month = (int)date('m', $date);
        $this->day = (int)date('d', $date);
    }

    public function index()
    {
        return $this->forTemplate();
    }

    public function setDayLink($dayLink)
    {

    }

    public function forTemplate()
    {
        Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
        Requirements::javascript(THIRDPARTY_DIR . '/jquery-livequery/jquery.livequery.js');
        Requirements::javascript('calendar/javascript/calendarsmall.js');
        return $this->showEvents ? $this->renderWith('CalendarLarge') : $this->renderWith('Calendar');
    }

    public function Name()
    {
        return $this->name;
    }

    public function Month()
    {
        return $this->month;
    }

    public function LongMonth()
    {
        $date = "{$this->year}-{$this->month}-1";
        return date('F', strtotime($date));
    }

    public function Year()
    {
        return $this->year;
    }

    public function Rows()
    {
        $daysInMonth = date('t', mktime(0, 0, 0, $this->month));

        // Figure out which day the week starts on
        $date = "{$this->year}-{$this->month}-01";
        $dayWeekStartsOn = (int)date('N', strtotime($date));

        $rows = new ArrayList();
        $row = new ArrayList();

        $dayOfWeek = 1;
        $dayOfMonth = 1;

        // Add blanks for first row
        while ($dayOfWeek != $dayWeekStartsOn) {
            $row->push(new ArrayData(array(
                'DayExists' => false,
            )));

            $dayOfWeek++;
        }

        // Add actual dates
        while ($dayOfMonth <= $daysInMonth) {
            if ($row->Count() >= 7) {
                $rows->push(new ArrayData(array('Days' => $row)));
                $row = new ArrayList();
            }

            if ($this->calendarProvider) {
                $events = $this->calendarProvider->getCalendarItems($this->year, $this->month, $dayOfMonth);
            } else {
                $events = false;
            }

            if ($this->linkTemplate) {
                $link = str_replace('$year', $this->year, $this->linkTemplate);
                $link = str_replace('$month', $this->month, $this->linkTemplate);
                if ($this->day) {
                    $link = str_replace('$day', $this->day, $this->linkTemplate);
                }
            } elseif ($events) {
                $link = $this->controller->Link() . '?calendardate=' . "{$this->year}-{$this->month}-$dayOfMonth";
            } else {
                $link = false;
            }

            $row->push(new ArrayData(array(
                'DayExists'  => true,
                'Number'     => $dayOfMonth,
                'Events'     => $events,
                'DayLink'    => $link,
                'Controller' => $this->controller,
                'Year'       => $this->year,
                'Month'      => $this->month,
                'Day'        => $dayOfMonth,
                'Selected'   => ($this->day == $dayOfMonth),
                'IsInFuture' => ($dayOfMonth > $this->day),
            )));

            $dayOfMonth++;
        }

        // Add blanks for last row
        if ($row->Count() >= 1) {
            while ($row->Count() < 7) {
                $row->push(new ArrayData(array(
                    'DayExists' => false,
                )));
            }

            $rows->push(new ArrayData(array('Days' => $row)));
        }

        return $rows;
    }

    public function Events()
    {
        if ($this->calendarProvider) {
            return $this->calendarProvider->getCalendarItems($this->year, $this->month, $this->day);
        }
    }

    public function PrevLink()
    {
        $month = $this->month;
        $year = $this->year;
        if (--$month < 1) {
            $year--;
            $month = 12;
        }

        $link = $this->controller->Link();
        return ((strpos($link, '?') !== false) ? "$link&amp;" : "$link?") . "calendardate=$year-$month-1";
    }

    function NextLink()
    {
        $month = $this->month;
        $year = $this->year;
        if (++$month > 12) {
            $year++;
            $month = 1;
        }

        $link = $this->controller->Link();
        return ((strpos($link, '?') !== false) ? "$link&amp;" : "$link?") . "calendardate=$year-$month-1";
    }

    public function Url()
    {
        return $this->controller->Link($this->name);
    }
}
