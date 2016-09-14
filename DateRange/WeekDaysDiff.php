<?php
function weekDaysDiff(\DateTime $date1, \DateTime $date2)
{
    $date1 = (clone $date1)->setTime(0, 0, 0);
    $date2 = (clone $date2)->setTime(0, 0, 0);
    if ($date1 > $date2){
        $date1origin = $date1;
        $date1 = $date2;
        $date2 = $date1origin;
    }
    $interval = DateInterval::createFromDateString('1 day');
    /** @var \DateTime[] $period */
    $period = new DatePeriod($date1, $interval, $date2);
    $weekdays = 0;
    foreach ($period as $date){
        if ($date->format('N') === '6' || $date->format('N') === '7'){
            $weekdays++;
        }
    }
    $daysDifference = (int)$date1->diff($date2)->format('%a');
    if ($date1 < $date2 && ($date1->format('N') === '6' || $date1->format('N') === '7')){
        $daysDifference++;
    }
    return $daysDifference - $weekdays;
}
// tests:
$date = new \DateTime('06.09.2016');
echo weekDaysDiff($date, $date) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('+1 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('+2 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('+3 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('+4 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('+5 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('+6 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('+7 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('+8 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('+9 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('+10 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('-1 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('-2 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('-3 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('-4 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('-5 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('-6 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('-7 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('-8 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('-9 weekdays')) . PHP_EOL;
echo weekDaysDiff($date, (clone $date)->modify('-10 weekdays')) . PHP_EOL;