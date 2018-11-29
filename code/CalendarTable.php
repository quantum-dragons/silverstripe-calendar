<?php
/**
 * CalendarTable displays all calendar items in a table list format. Shows
 * current month only, but allows to switch to other months via ajax requests.
 *
 * @package calendar
 */

class CalendarTable extends Calendar
{

    public function forTemplate()
    {
        Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
        Requirements::javascript(THIRDPARTY_DIR . '/jquery-livequery/jquery.livequery.js');
        Requirements::javascript('calendar/javascript/calendarsmall.js');
        return $this->renderWith('CalendarTable');
    }

    /**
     * Fetch all items.
     *
     * @return DataObjectSet
     */
    public function Rows()
    {
        $dayOfMonth = 1;
        $months = new ArrayList();

        // just one month
        if ($this->calendarProvider && $this->calendarProvider instanceof CalendarTableProvider) {
            $events = $this->calendarProvider->getCalendarItemsInMonth($this->year, $this->month);
        } else {
            $events = null;
        }
        // inject amendments
        if ($events) {
            foreach ($events as $event) {
                // date fields might be named differently
                $StartDate = $event->{$this->calendarProvider->getStartDateFieldName()};
                $FinishDate = $event->{$this->calendarProvider->getFinishDateFieldName()};

                // rewrite the field names to 'StartDate' and 'FinishDate'
                if ($StartDate) {
                    $startDateField = new Date();
                    $startDateField->setValue($StartDate);
                    $event->StartDate = $startDateField;
                }

                if ($FinishDate) {
                    $finishDateField = new Date();
                    $finishDateField->setValue($FinishDate);
                    $event->FinishDate = $finishDateField;
                }

                // tag this event if overflowing from previous month
                if ($event->StartDate && strtotime($event->StartDate->getValue()) < strtotime($this->CurrentPageDate())) {
                    $event->StartedBeforeThisMonth = true;
                }

                // tag this event if overflowing past this month
                if ($event->FinishDate) {
                    $time = strtotime($this->CurrentPageDate());
                    $nextMonth = strtotime('+1 month', $time);
                    if (strtotime($event->FinishDate->getValue()) >= $nextMonth) {
                        $event->EndsAfterThisMonth = true;
                    }
                }

                // tag this event if is in future
                if (strtotime($event->StartDate) > time()) {
                    $event->InFuture = true;
                }

                $months->push($event);
            }
        }

        return new ArrayData(array(
            'Events'    => $months,
            'LongMonth' => $this->LongMonth(),
            'Year'      => $this->Year(),
        ));
    }

    public function CurrentPageDate()
    {
        return "$this->year-$this->month-1";
    }
}
