<?php

namespace YaPro\Helper\Date;

class DateTimeHelper
{
    /**
     * @param string $date
     * @param \DateTimeZone|NULL $timezone
     * @return \DateTime
     * @throws \UnexpectedValueException
     */
    public function create($date, \DateTimeZone $timezone = NULL): \DateTime
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
    public function createImmutable($date, \DateTimeZone $timezone = NULL): \DateTimeImmutable
    {
        $dateTime = new \DateTimeImmutable($date, $timezone);
        $result = \DateTimeImmutable::getLastErrors();
        if ($result['warning_count']) {
            throw new \UnexpectedValueException(implode(', ', $result['warnings']));
        }
        return $dateTime;
    }

    /**
     * @param \DateTimeInterface $from
     * @param \DateTimeInterface $to
     * @return \DateTimeInterface[]
     */
    public function getMonthlyIntervals(\DateTimeInterface $from, \DateTimeInterface $to): array
    {
        $intervals = [];
        $from = clone $from;
        $to = clone $to;
        $startDate = $from->modify('first day of this month')->setTime(0, 0, 0);
        $endDate = $to->modify('last day of this month')->setTime(23, 59, 59);
        while ($startDate < $endDate) {
            $firstDay = clone $startDate;
            $startDate = $startDate->modify('last day of this month')->setTime(23, 59, 59);
            $intervals[] = [
                'firstDay' => $firstDay,
                'lastDay' => clone $startDate,
            ];
            $startDate = $startDate->modify('+1 second');
        }
        return $intervals;
    }

    /**
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @return \DateTimeImmutable[]
     */
    public function getIntervalDays(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        $days = [];
        $startDate = clone $startDate;
        while ($startDate <= $endDate) {
            $days[] = $startDate->setTime(0,0,0);
            $startDate = (clone $startDate)->modify('+1 day');
        }
        return $days;
    }

    /**
     * @param array $dates
     * @return \DateTime[]
     */
    public function getDatesFromStringsArray(array $dates): array
    {
        $result = [];
        foreach ($dates as $date) {
            $result[] = $this->create($date);
        }
        return $result;
    }

    /**
     * @param \DateTimeInterface $date1
     * @param \DateTimeInterface $date2
     * @return int
     */
    public function getNumberDaysDiff(\DateTimeInterface $date1, \DateTimeInterface $date2): int
    {
        // between 2018-12-29 and 2018-12-29 diff = 0
        // between 2018-12-29 and 2018-12-30 diff = 1
        // between 2018-12-29 and 2018-12-31 diff = 2
        return filter_var($date1->diff($date2)->format('%a'), FILTER_VALIDATE_INT);
    }

    /**
     * function can check is are a date in the dates range
     * @param string $date
     * @param string $minDate
     * @param string $maxDate
     * @return \DateTimeImmutable|null
     */
    public function getDateInTheDatesRange(string $date, string $minDate, string $maxDate)
    {
        $dateTime = $this->createImmutable($date);
        if ($this->createImmutable($minDate) <= $dateTime && $dateTime <= $this->createImmutable($maxDate)) {
            return $dateTime;
        }
        return null;
    }
}