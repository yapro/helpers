DateRange
---

DateRangeHelper can check is are a date in the dates range. Example usage:

```php
<?php
try {
	$dateRangeHelper = new DateRangeHelper('1900-01-01', '2015-08-01');
	$birthdate = $dateRangeHelper->getValidDate($customer['birthdate']);
} catch (\Exception $e) {
	$birthdate = null;
}
```

DateTimeUtility can help you to make a date`s object with validation of date. Example usage:

```php
	<?php
	try {
$dateRangeHelper = new DateRangeHelper('1900-01-01', '2015-08-01');
$birthdate = $dateRangeHelper->getValidDate($customer['birthdate']);
} catch (\Exception $e) {
$birthdate = null;
}
```