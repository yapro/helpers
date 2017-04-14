Computes the difference of multidimensional arrays using keys for comparison.
---

Example:
```php
<?php
$input = ['blue'  => 1, 'white' => ['purple' => 4, 'green' => 3], 'red' => 2];
$filter = ['blue' => 6, 'white' => ['yellow' => 7, 'green' => 5], 'red' => 2];

print_r(MultidimensionalArrayHelper::diffKeys($input, $filter));
```
Result:
```
Array
(
    [white] => Array
        (
            [purple] => 4
        )

)
```