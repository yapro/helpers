DateRange
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

DateTimeUtility can help you to make a date`s object with validation of date. Example usage:

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