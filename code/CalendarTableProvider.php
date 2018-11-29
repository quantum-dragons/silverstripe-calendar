<?php
/**
 * CalendarTableProvider interface used by CalendarTable.
 *
 * @package calendar
 */

interface CalendarTableProvider
{
    /**
     * Return all calendar items starting in the given month.
     *
     * @param year
     * @param month
     *
     * @return DataObjectSet
     */
    public function getCalendarItemsInMonth($year, $month);

    /**
     * Set it to return the name of the start date field on the actual event DataObject
     *
     * @return string database field name
     */
    public function getStartDateFieldName();

    /**
     * Set it to return the name of the finish date field on the actual event DataObject
     *
     * @return string database field name
     */
    public function getFinishDateFieldName();
}
