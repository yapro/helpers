<?php

namespace YaPro\Helper\Date;

use YaPro\Helper\Validation\ScalarValidator;

class DateTimeHelper
{
    /**
     * @param string $date
     * @param \DateTimeZone|NULL $timezone
     * @return \DateTime
     * @throws \UnexpectedValueException
     */
    public static function create($date, \DateTimeZone $timezone = NULL): \DateTime
    {
        $dateTime = new \DateTime($date, $timezone);
        $result = \DateTime::getLastErrors();
        if ($result['warning_count']) {
            throw new \UnexpectedValueException(implode(', ', $result['warnings']));
        }
        return $dateTime;
    }

    /**
     * @param string $date
     * @param \DateTimeZone|NULL $timezone
     * @return \DateTimeImmutable
     * @throws \UnexpectedValueException
     */
    public static function createImmutable($date, \DateTimeZone $timezone = NULL): \DateTimeImmutable
    {
        $dateTime = new \DateTimeImmutable($date, $timezone);
        $result = \DateTimeImmutable::getLastErrors();
        if ($result['warning_count']) {
            throw new \UnexpectedValueException(implode(', ', $result['warnings']));
        }
        return $dateTime;
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return array
     */
    public static function getMonthlyIntervals(\DateTime $from, \DateTime $to): array
    {
        $intervals = [];
        $from = clone $from;
        $to = clone $to;
        $startDate = $from->modify('first day of this month');
        $endDate = $to->modify('last day of this month');
        while ($startDate < $endDate) {
            $firstDay = clone $startDate;
            $startDate->modify('last day of this month')->modify('+1 day')->modify('-1 second');
            $lastDay = clone $startDate;
            $intervals[] = [
                'firstDay' => $firstDay,
                'lastDay' => $lastDay,
            ];
            $startDate->modify('+1 second');
        }
        return $intervals;
    }

    /**
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return \DateTime[]
     */
    public static function getIntervalDays(\DateTime $startDate, \DateTime $endDate): array
    {
        $days = [];
        $startDate = clone $startDate;
        while ($startDate < $endDate) {
            $days[] = $startDate->setTime(0,0,0);
            $startDate = (clone $startDate)->modify('+1 day');
        }
        return $days;
    }

    /**
     * @param array $dates
     * @return \DateTime[]
     */
    public static function getDates(array $dates): array
    {
        $result = [];
        foreach ($dates as $date) {
            $result[] = self::create($date);
        }
        return $result;
    }

    /**
     * @param \DateTime $date1
     * @param \DateTime $date2
     * @return int
     */
    public function getNumberDaysDiff(\DateTime $date1, \DateTime $date2): int
    {
        // between 2018-12-29 and 2018-12-29 diff = 0
        // between 2018-12-29 and 2018-12-30 diff = 1
        // between 2018-12-29 and 2018-12-31 diff = 2
        return ScalarValidator::getInteger($date1->diff($date2)->format('%a'));
    }
}