<?php
declare(strict_types = 1);

namespace YaPro\Helper\Date;

class WorkdaysHelper
{
    /**
     * @var \DateTime[]
     */
    private $holidays;

    /**
     * @var \DateTime[]
     */
    private $workdays;

    /**
     * @var array
     */
    private $weekend = ['6', '7'];

    public function __construct(array $holidays, array $workdays, array $weekend = [])
    {
        $this->holidays = $holidays;
        $this->workdays = $workdays;
        if (!empty($weekend)){
            $this->weekend = $weekend;
        }
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    public function isWorkDay(\DateTime $date): bool
    {
        if (in_array($date, $this->workdays)) {
            return true;
        }
        return in_array($date, $this->holidays) || $this->isWeekend($date) ? false : true;
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
        return in_array($date->format('N'), $this->weekend, true);
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
        $sign = $workdays > 0 ? '' : '-';
        $workdaysAbsolute = abs($workdays);
        while ($workdaysAbsolute > 0) {
            $date->modify($sign . '1 day');
            if ($this->isWorkDay($date)) {
                $workdaysAbsolute--;
            }
        }

        return $date;
    }
}