<?php
function weekDaysDiff(\DateTime $date1, \DateTime $date2)
{
    $date1 = (clone $date1)->setTime(0,0,0);
    $date2 = (clone $date2)->setTime(0,0,0);
    $daysDifference = $date1->diff($date2)->format('%d');
    if ((clone $date1)->modify('+' . ($daysDifference - 2) . ' weekdays') == $date2) {
        return $daysDifference - 2;
    } else {
        return $daysDifference;
    }
}
// tests:
$date = new \DateTime('01.09.2016');
$date1 = (clone $date)->modify('+1 weekdays');
$date2 = (clone $date)->modify('+2 weekdays');
$date5 = (clone $date)->modify('+5 weekdays');
echo weekDaysDiff($date, $date1) . PHP_EOL;
echo weekDaysDiff($date, $date2) . PHP_EOL;
echo weekDaysDiff($date, $date5) . PHP_EOL;