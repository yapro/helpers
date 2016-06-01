DateRangeHelper can check is are a date in the dates range.
---

Example usage:

```
<?php
class foo {
	function __construct(){
		$this->dateRangeHelper = new DateRangeHelper('1900-01-01', '2015-08-01');
	}
	function bar(){
		try {
            $birthdate = $this->dateRangeHelper->getValidDate($customer['birthdate']);
        } catch (\Exception $e) {
            $birthdate = null;
        }
	}
}
```