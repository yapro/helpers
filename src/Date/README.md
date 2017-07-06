Date Helpers
---

DateTimeHelper can help you to make a date`s object with validation of date. Example usage:

```php
<?php
try {
    $dateTimeHelper = new DateTimeHelper();
    $first = new \DateTime('1980-01-01');
    $second = $dateTimeHelper->create('2000-01-01');
    $dateTimeHelper->createImmutable('2000-01-01');
    $dateTimeHelper->getMonthlyIntervals($first, $second);
    $dateTimeHelper->getIntervalDays($first, $second);
    $dateTimeHelper->getDifferenceInWorkingDays($first, $second);
    $dateTimeHelper->getDateInTheDatesRange('1970-07-07', '1980-08-08', '2012-12-12');
} catch (\Exception $e) {
    // ...
}
```

WorkdaysHelper can help you to working with work days. Example usage:

```php
<?php
// setup
$dateTimeHelper = new DateTimeHelper();
$folder = realpath(__DIR__ . '/src/Date');
$holidays = $dateTimeHelper->getDates(
    explode(PHP_EOL, FileHelper::getFileContent($folder . '/holidays.txt'))
);
$workdays = $dateTimeHelper->getDates(
    explode(PHP_EOL, FileHelper::getFileContent($folder . '/workdays.txt'))
);
$weekend = ['6', '7'];// ISO-8601 numeric representation of the day of the week
$workdaysHelper = new WorkdaysHelper($holidays, $workdays, $weekend);
// use
$firstDate = $dateTimeHelper->create('2000-01-01');
$workdaysHelper->isWorkDay($firstDate);
$workdaysHelper->getDateWithModifiedWorkdays($firstDate, -5);
$secondDate = $dateTimeHelper->create('2000-02-01');
$workdaysHelper->getDifferenceInWorkingDays(
    $firstDate,
    $secondDate
);
```