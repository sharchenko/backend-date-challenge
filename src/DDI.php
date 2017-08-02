<?php

namespace Vice\Challenge;

/**
 * Class DDI
 * @package Vice\Challenge
 */
class DDI implements DateDifference
{
    /**
     * @var int
     */
    private $start_year;

    /**
     * @var int
     */
    private $start_month;

    /**
     * @var int
     */
    private $start_day;

    /**
     * @var int
     */
    private $end_year;

    /**
     * @var int
     */
    private $end_month;

    /**
     * @var int
     */
    private $end_day;

    const YEAR_DAYS = 365;
    const LEAP_YEAR_DAYS = 366;

    const MONTH_TO_DAY_MAP = array(
        1 => 31,
        2 => 28,
        3 => 31,
        4 => 30,
        5 => 31,
        6 => 30,
        7 => 31,
        8 => 31,
        9 => 30,
        10 => 31,
        11 => 30,
        12 => 31,
    );

    const LEAP_MONTH_TO_DAY_MAP = array(
        1 => 31,
        2 => 29,
        3 => 31,
        4 => 30,
        5 => 31,
        6 => 30,
        7 => 31,
        8 => 31,
        9 => 30,
        10 => 31,
        11 => 30,
        12 => 31,
    );

    /**
     * DDI constructor
     *
     * @param $start
     * @param $end
     */
    public function __construct($start, $end)
    {
        if ($this->validate($start) && $this->validate($end)) {
            $this->start_year = $start['year'];
            $this->start_month = $start['month'];
            $this->start_day = $start['day'];
            $this->end_year = $end['year'];
            $this->end_month = $end['month'];
            $this->end_day = $end['day'];
        } else {
            throw new \InvalidArgumentException('Date input is not valid');
        }
    }

    /**
     * @param $month
     * @param $day
     * @param $init
     * @return int
     */
    private static function daysCount($map, $month, $day, $init = 0)
    {
        foreach (range(1, $month) as $m) {
            if ($m > 0) {
                $init += $map[$m];
            }
        }

        return $init + $day;
    }

    /**
     * @param $start_year
     * @param $start_month
     * @param $start_day
     * @return int
     */
    private static function getDaysFromStartOfYear($start_year, $start_month, $start_day)
    {
        return self::daysCount(
            self::isLeapYear($start_year) ? self::LEAP_MONTH_TO_DAY_MAP : self::MONTH_TO_DAY_MAP,
            $start_month,
            $start_day
        );
    }

    /**
     * @return int
     */
    public function getDifferenceInYears()
    {
        return $this->end_year - $this->start_year;
    }

    /**
     * @return int
     */
    public function getDifferenceInMonths()
    {
        return $this->end_month - $this->start_month;
    }

    /**
     * @return int
     */
    public function getDifferenceInDays()
    {
        return $this->end_day - $this->start_day;
    }

    /**
     * @return int
     */
    public function getTotalDifferenceInDays()
    {
        $days_in_years_between = 0;

        foreach (range($this->start_year + 1, $this->end_year - 1) as $y) {
            if ($this->getDifferenceInYears() > 1) {
                $days_in_years_between += self::isLeapYear($y) ? self::LEAP_YEAR_DAYS : self::YEAR_DAYS;
            }
        }

        $start_year_days_count = self::isLeapYear($this->start_year) ? self::LEAP_YEAR_DAYS : self::YEAR_DAYS;

        return $start_year_days_count -
            $this->getDaysTillStart() +
            $days_in_years_between +
            $this->getDaysTillEnd();
    }

    /**
     * @param $year
     * @return bool
     */
    private static function isLeapYear($year)
    {
        $result = false;
        $r4 = $year % 4;
        $r100 = $year % 100;
        $r400 = $year % 400;

        if ($r4 == 0) {
            $result = true;
            if ($r100 == 0) {
                $result = false;
                if ($r400 == 0) {
                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     * @param $date
     * @return bool
     */
    private function validate($date)
    {
        $result = true;

        if (self::isLeapYear($date["year"]) && $date["month"] == 2 && $date["day"] > 29) {
            $result = false;
        } elseif (!self::isLeapYear($date["year"]) && $date["month"] == 2 && $date["day"] > 28) {
            $result = false;
        } elseif (!in_array($date["month"], [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])) {
            $result = false;
        } elseif ($date["day"] > self::MONTH_TO_DAY_MAP[$date["month"]]) {
            $result = false;
        }

        return $result;
    }

    /**
     * @return int
     */
    private function getDaysTillEnd()
    {
        return self::getDaysFromStartOfYear($this->end_year, $this->end_month, $this->end_day);
    }

    /**
     * @return int
     */
    private function getDaysTillStart()
    {
        return self::getDaysFromStartOfYear($this->start_year, $this->start_month, $this->start_day);
    }
}