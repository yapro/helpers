<?php
declare(strict_types = 1);

namespace YaPro\Helper\Date;

class WorkdaysHelper
{
    const SATURDAY = '6';
    const SUNDAY = '7';
    /**
     * @var \DateTime[]
     */
    private $holidays;

    /**
     * @var \DateTime[]
     */
    private $workdays;

    public function __construct(array $holidays, array $workdays)
    {
        $this->holidays = $holidays;
        $this->workdays = $workdays;
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    public function isWorkDay(\DateTime $date): bool
    {
        return in_array($date, $this->workdays) || (!in_array($date, $this->holidays) && !$this->isWeekend($date));
    }

    /**
     * @param \DateTime $date1
     * @param \DateTime $date2
     * @return int
     */
    public function getDifferenceInWorkingDays(\DateTime $date1, \DateTime $date2): int
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
        $period = new \DatePeriod($date1, $interval, $date2->modify('1 day'));
        $result = 0;
        foreach ($period as $date) {
            if ($this->isWorkDay($date)) {
                $result++;
            }
        }

        return $result;
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    private function isWeekend(\DateTime $date): bool
    {
        return $date->format('N') === self::SATURDAY || $date->format('N') === self::SUNDAY;
    }

    /**
     * @param \DateTime $date
     * @param int $workdays
     * @return \DateTime
     */
    public function getDateWithModifiedWorkdays(\DateTime $date, int $workdays): \DateTime
    {
        if ($workdays === 0) {
            return $date;
        }
        $someDate = clone $date;
        $sign = $workdays > 0 ? '' : '-';
        $workdaysAbsolute = abs($workdays);
        while ($workdaysAbsolute > 0) {
            $someDate->modify($sign . '1 day');
            if ($this->isWorkDay($someDate)) {
                $date->modify($sign . '1 day');
                $workdaysAbsolute--;
            }
        }

        return $date;
    }
}