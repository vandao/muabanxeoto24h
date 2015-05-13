<?php

class DateRange extends \Phalcon\Mvc\User\Component {
    
    private $_dateRangeFunctions = array(
        'Previous Day'   => 'previousDay',
        'Next Day'       => 'nextDay',
        'Today'          => 'today',
        'Yesterday'      => 'yesterday',
        'Previous Week'  => 'previousWeek',
        'This Week'      => 'thisWeek',
        'Next Week'      => 'nextWeek',
        'Last Week'      => 'lastWeek',
        'Previous Month' => 'previousMonth',
        'This Month'     => 'thisMonth',
        'Next Month'     => 'nextMonth',
        'Last Month'     => 'lastMonth',
        'Last 2 Months'  => 'last2Months',
        'Last 3 Months'  => 'last3Months',
        'Last 30 Days'   => 'last30Days',
        'Last 60 Days'   => 'last60Days',
        'Last 90 Days'   => 'last90Days',
    );

    private $_time = array();

    public function __construct() {
        $this->_time = array(
            'day'    => 60*60*24,
            'week'   => 60*60*24*7,
            'month'  => 60*60*24*30,
        );
    }

    /**
     * Get all day in month from day to day
     * @param string $dateFrom (04-05-2011)
     * @param string $dayTo (10-05-2011)
     * @return array day
     */
    public function getAllDayFromDayToDay($dateFrom, $dateTo) {
        $dateTimestampFrom = strtotime($dateFrom);
        $dateTimestampTo = strtotime($dateTo);
        $dateTimestampRange = $dateTimestampTo - $dateTimestampFrom;
        $numberOfDateHaveLogs = $dateTimestampRange / (60*60*24);

        $days = array(date('Y-m-d', $dateTimestampFrom));
        for ($i = 1; $i <= $numberOfDateHaveLogs; $i++) {
            $days[] = date('Y-m-d', $dateTimestampFrom + (60*60*24*$i));
        }

        return $days;
    }

    public function direct($orders = array('Today', 'Yesterday', 'This Week', 'Last Week', 'This Month', 'Last Month'), $date = '', $useCurrentOrder = true) {
        $dateRanges = array();

        foreach ($orders as $order) {
            if (is_numeric(stripos($order, 'Last')) && is_numeric(stripos($order, 'Month'))) {
                list(,$number) = explode(' ', $order);
                if (is_numeric($number)) {
                    $dateRanges[$order] = $this->_theMonthBefore($number--);
                } else {
                    $functionName = '_lastMonth';
                    $dateRanges[$order] = $this->$functionName($date);
                }
            } elseif (is_numeric(stripos($order, 'Last')) && is_numeric(stripos($order, 'Days'))) {
                list(,$number) = explode(' ', $order);
                if (is_numeric($number)) {
                    $dateRanges[$order] = $this->_theDayBefore($number--);
                }
            } elseif (is_numeric(stripos($order, 'Next')) && is_numeric(stripos($order, 'Month'))) {
                list(,$number) = explode(' ', $order);
                if (is_numeric($number)) {
                    $dateRanges[$order] = $this->_theMonthAfter($number++);
                } else {
                    $functionName = '_nextMonth';
                    $dateRanges[$order] = $this->$functionName($date);
                }
            } else {
                $functionName = '_' . $this->_dateRangeFunctions[$order];
                $dateRanges[$order] = $this->$functionName($date);
            }
        }

        $newDateRanges = array();
        if (! $useCurrentOrder) {
            foreach ($dateRanges as $dateRange) {
                $newDateRanges[] = $dateRange;
            }

            return $newDateRanges;
        }
        
        return $dateRanges;
    }

    private function _previousMonth($date) {
        $time     = strtotime($date);

        $firstDay = strtotime('first day of previous month', $time);
        $lastDay  = strtotime('last day of previous month', $time);

        return $this->_convertDate($firstDay, $lastDay);
    }

    private function _thisMonth() {
        $time     = strtotime('now');

        $firstDay = strtotime('first day of this month', $time);
        $lastDay  = strtotime('last day of this month', $time);

        return $this->_convertDate($firstDay, $lastDay);
    }

    private function _lastMonth() {
        $time     = strtotime('now');

        $firstDay = strtotime('first day of previous month', $time);
        $lastDay  = strtotime('last day of previous month', $time);

        return $this->_convertDate($firstDay, $lastDay);
    }

    private function _nextMonth($date) {
        $time     = strtotime($date);

        $firstDay = strtotime('first day of next month', $time);
        $lastDay  = strtotime('last day of next month', $time);

        return $this->_convertDate($firstDay, $lastDay);
    }

    private function _theMonthBefore($before) {
        $time     = strtotime("-$before month");

        $firstDay = strtotime('first day of previous month', $time);
        $lastDay  = strtotime('last day of previous month', $time);

        return $this->_convertDate($firstDay, $lastDay);
    }

    private function _theMonthAfter($after) {
        $time     = strtotime("+$after month");

        $firstDay = strtotime('first day of previous month', $time);
        $lastDay  = strtotime('last day of previous month', $time);

        return $this->_convertDate($firstDay, $lastDay);
    }

    private function _previousWeek($date) {
        $time      = strtotime('-1 week', strtotime($date));
        $mondayKey = (date('l', $time) == 'Sunday') ? 'Monday last week' : 'Monday this week';

        $monday    = strtotime($mondayKey, $time);
        $sunday    = strtotime("Sunday this week", $monday);

        return $this->_convertDate($monday, $sunday);
    }

    private function _thisWeek() {
        $time      = strtotime('now');
        $mondayKey = (date('l', $time) == 'Sunday') ? 'Monday last week' : 'Monday this week';

        $monday    = strtotime($mondayKey, $time);
        $sunday    = strtotime("Sunday this week", $monday);

        return $this->_convertDate($monday, $sunday);
    }

    private function _lastWeek() {
        $time      = strtotime('-1 week');
        $mondayKey = (date('l', $time) == 'Sunday') ? 'Monday last week' : 'Monday this week';

        $monday    = strtotime($mondayKey, $time);
        $sunday    = strtotime("Sunday this week", $monday);

        return $this->_convertDate($monday, $sunday);
    }

    private function _nextWeek($date) {
        $time      = strtotime('+1 week', strtotime($date));
        $mondayKey = (date('l', $time) == 'Sunday') ? 'Monday last week' : 'Monday this week';

        $monday    = strtotime($mondayKey, $time);
        $sunday    = strtotime("Sunday this week", $monday);

        return $this->_convertDate($monday, $sunday);
    }

    private function _previousDay($date) {
        $time = strtotime('-1 day', strtotime($date));

        return $this->_convertDate($time, $time);
    }

    private function _nextDay($date) {
        $time = strtotime('+1 day', strtotime($date));

        return $this->_convertDate($time, $time);
    }

    private function _today() {
        $time = strtotime('now');

        return $this->_convertDate($time, $time);
    }

    private function _yesterday() {
        $time = strtotime('-1 day');

        return $this->_convertDate($time, $time);
    }

    private function _theDayBefore($before) {
        $time = strtotime("-$before day");

        return $this->_convertDate($time, $time);
    }

    private function _convertDate($from, $to) {
        // echo date('d-m-Y', $from);
        // echo "<br />";
        // echo date('d-m-Y', $to);
        // exit;

        return array('From' => date('d-m-Y', $from), 'To' => date('d-m-Y', $to));
    }
}