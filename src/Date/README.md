Date Helpers
---

DateRangeHelper can check is are a date in the dates range. Example usage:

```php
<?php
try {
    $dateRangeHelper = new DateRangeHelper('1980-08-08', '2012-12-12');
    $birthdate = $dateRangeHelper->getValidDate('1970-07-07');
} catch (\Exception $e) {
    $birthdate = null;
}
```

DateTimeHelper can help you to make a date`s object with validation of date. Example usage:

```php
<?php
try {
    $first = new \DateTime('1980-01-01');
    $second = DateTimeHelper::create('2000-01-01');
    DateTimeHelper::createImmutable('2000-01-01');
    DateTimeHelper::getMonthlyIntervals($first, $second);
    DateTimeHelper::getIntervalDays($first, $second);
    DateTimeHelper::getDifferenceInWorkingDays($first, $second);
} catch (\Exception $e) {
    // ...
}
```

WorkdaysHelper can help you to working with work days. Example usage:

```php
<?php
// setup
$folder = realpath(__DIR__ . '/src/Date');
$holidays = DateTimeHelper::getDates(
    explode(PHP_EOL, FileHelper::getFileContent($folder . '/holidays.txt'))
);
$workdays = DateTimeHelper::getDates(
    explode(PHP_EOL, FileHelper::getFileContent($folder . '/workdays.txt'))
);
$weekend = ['6', '7'];// ISO-8601 numeric representation of the day of the week
$workdaysHelper = new WorkdaysHelper($holidays, $workdays, $weekend);
// use
$firstDate = DateTimeHelper::create('2000-01-01');
$workdaysHelper->isWorkDay($firstDate);
$workdaysHelper->getDateWithModifiedWorkdays($firstDate, -5);
$secondDate = DateTimeHelper::create('2000-02-01');
$workdaysHelper->getDifferenceInWorkingDays(
    $firstDate,
    $secondDate
);
```