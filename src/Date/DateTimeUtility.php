<?php

namespace YaPro\Helper\Date;

class DateTimeUtility
{
    /**
     * @param string $date
     * @param \DateTimeZone|NULL $timezone
     * @return \DateTime
     * @throws \UnexpectedValueException
     */
    public static function create($date, \DateTimeZone $timezone = NULL)
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
    public static function createImmutable($date, \DateTimeZone $timezone = NULL)
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
    public static function getMonthlyIntervals(\DateTime $from, \DateTime $to)
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
    public static function getIntervalDays(\DateTime $startDate, \DateTime $endDate)
    {
        $days = [];
        $startDate = clone $startDate;
        while ($startDate < $endDate) {
            $days[] = $startDate->setTime(0,0,0);
            $startDate = (clone $startDate)->modify('+1 day');
        }
        return $days;
    }

    public static function getDifferenceInWorkingDays(\DateTime $date1, \DateTime $date2)
    {
        $date1 = (clone $date1)->setTime(0, 0, 0);
        $date2 = (clone $date2)->setTime(0, 0, 0);
        if ($date1 > $date2) {
            $date1origin = $date1;
            $date1 = $date2;
            $date2 = $date1origin;
        }
        $interval = \DateInterval::createFromDateString('1 day');
        /** @var \DateTime[] $period */
        $period = new \DatePeriod($date1, $interval, $date2);
        $weekdays = 0;
        foreach ($period as $date) {
            if ($date->format('N') === '6' || $date->format('N') === '7') {
                $weekdays++;
            }
        }
        $daysDifference = filter_var($date1->diff($date2)->format('%a'), FILTER_VALIDATE_INT);
        if ($date1 < $date2 && ($date1->format('N') === '6' || $date1->format('N') === '7')) {
            $daysDifference++;
        }

        return $daysDifference - $weekdays;
    }
}