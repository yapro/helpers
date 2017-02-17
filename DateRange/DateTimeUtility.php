<?php

namespace com\calltouch\api\utility;

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
}