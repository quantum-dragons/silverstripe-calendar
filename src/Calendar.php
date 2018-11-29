<?php

namespace QuantumDragons\Calendar;

use SilverStripe\Control\RequestHandler;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;

class Calendar extends RequestHandler
{
    /**
     * Calendar constructor.
     *
     * @param $controller
     * @param $name
     * @param $calendarProvider
     */
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

    /**
     * @return DBHTMLText
     */
    public function index()
    {
        return $this->forTemplate();
    }

    public function setDayLink($dayLink)
    {
        //
    }

    /**
     * @return DBHTMLText
     */
    public function forTemplate()
    {
        Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
        Requirements::javascript(THIRDPARTY_DIR . '/jquery-livequery/jquery.livequery.js');
        Requirements::javascript('QuantumDragons/Calendar: client/javascript/calendarsmall.js');

        return $this->showEvents ? $this->renderWith('CalendarLarge') : $this->renderWith('Calendar');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return string
     */
    public function getLongMonth()
    {
        $date = "{$this->year}-{$this->month}-1";

        return date('F', strtotime($date));
    }

    /**
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return ArrayList
     */
    public function getRows()
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

    /**
     * @return mixed
     */
    public function getEvents()
    {
        if ($this->calendarProvider) {
            return $this->calendarProvider->getCalendarItems($this->year, $this->month, $this->day);
        }
    }

    public function getPrevLink()
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

    public function getNextLink()
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

    public function getUrl()
    {
        return $this->controller->Link($this->name);
    }
}
