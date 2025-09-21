<?php

namespace YaPro\Helper\Date;

class DateTimeHelper
{
    /**
     * этот метод следует использовать, т.к. может возникнуть следующая ситуация http://php.net/manual/ru/datetime.getlasterrors.php#102686
     * таким образом, бухгалтерский отчет с 1999-04-01 по 1999-04-31 может превратиться в отчет, включающий 1 мая
     * @param string $date
     * @param \DateTimeZone|NULL $timezone
     * @return \DateTime
     * @throws \UnexpectedValueException
     */
    public function create($date, \DateTimeZone $timezone = NULL): \DateTime
    {
        /* http://php.net/manual/ru/function.strtotime.php Замечание по str_replace:
        Даты в формате m/d/y или d-m-y разрешают неоднозначность с помощью анализа разделителей их элементов: если
        разделителем является слеш (/), то дата интерпретируется в американском формате m/d/y, если же разделителем
        является дефис (-) или точка (.), то подразумевается использование европейского формата d-m-y. Однако, если,
        год указан в двухзначном формате, а разделителем является дефис (-), строка даты интерпретируется как y-m-d. */
        // @todo вместо точки лучше использовать тире, потому что например 2019.06.26 вызывает ошибку спецификации
        $dateTime = new \DateTime(str_replace('/', '.', $date), $timezone);
        $result = \DateTime::getLastErrors();
        if ($result['warning_count'] ?? null) {
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
        if ($result['warning_count'] ?? null) {
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
            $days[] = $startDate->setTime(0, 0, 0);
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

    /**
     * Прибавляет указанное количество дней к дате
     * @param string $date
     * @param int $days
     * @return \DateTime
     * @throws \UnexpectedValueException
     */
    public function addDays($date, $days)
    {
        if ($days < 0) {
            throw new \UnexpectedValueException('Invalid amount of days: ' . $days);
        }

        $dateTime = $this->create($date);
        $dateTime->add(new \DateInterval('P' . $days . 'D'));

        return $dateTime;
    }

    /**
     * Вычитает указанное количество дней из даты
     * @param string $date
     * @param int $days
     * @return \DateTime
     * @throws \UnexpectedValueException
     * @throws \Exception
     */
    public function subDays($date, $days)
    {
        if ($days < 0) {
            throw new \UnexpectedValueException('Invalid amount of days: ' . $days);
        }

        $dateTime = $this->create($date);
        $dateTime->sub(new \DateInterval('P' . $days . 'D'));

        return $dateTime;
    }

    /**
     * Преобразует переданную дату в необходимый формат или вернет заменитель если передана пустая дата или
     * вернет пустую строку в случае передачи некорректной даты
     *
     * @param string $dateString Дата для преобразования
     * @param string $format Формат, в который следует пребразовать дату
     * @param string $replacement
     * @return string
     * @deprecated следует использовать DateTimeHelper::create
     */
    public function convertDateFormat($dateString, $format = 'd / m / Y', $replacement = '&nbsp;')
    {
        if (empty($dateString)) {
            return $replacement;
        }
        //если в дате в качестве разделителя указан '/' то strtotime преобразует строку из формата ymd
        $dateString = str_replace('/', '-', $dateString);

        try {
            $dateObject = new \DateTime($dateString);
        } catch (\Exception $e) {
            return '';
        }

        return $dateObject->format($format);

    }

    /**
     * Изменение формата даты/времени
     *
     * @param string $dateTime Строка с датой/временем
     * @param string $from Из формата
     * @param string $to В формат
     * @return string
     * @throws \UnexpectedValueException
     */
    public function changeFormat($dateTime, $from, $to)
    {
        $createdDateTime = \DateTime::createFromFormat($from, $dateTime);
        if ($createdDateTime == false) {
            throw new \UnexpectedValueException('Invalid date format: ' . $dateTime . '. Must be: ' . $from);
        }
        $result = $createdDateTime->format($to);

        return $result;
    }
}
