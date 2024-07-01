<?php
declare(strict_types = 1);

namespace YaPro\Helper;

use UnexpectedValueException;
use function ksort;

class ArrayHelper
{
    /**
     * Computes the difference of multidimensional arrays using keys for comparison.
     * @param array $first
     * @param array $second
     * @return array
     */
    public function multidimensionalDiffByKeys(array $first, array $second): array
    {
        if ($diff = array_diff_key($first, $second)) {
            return $diff;
        } else {
            foreach ($first as $key => $value) {
                if (is_array($value) && $diff = $this->multidimensionalDiffByKeys($value, $second[$key])) {
                    return [$key => $diff];
                }
            }
        }
        return [];
    }
    
    /**
     * @param array $array1
     * @param array $array2
     * @return array
     */
    function arrayDiffAssocMultidimensional(array $array1, array $array2): array
    {
        $difference = [];
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!array_key_exists($key, $array2)) {
                    $difference[$key] = $value;
                } elseif (!is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $multidimensionalDiff = $this->arrayDiffAssocMultidimensional($value, $array2[$key]);
                    if (count($multidimensionalDiff) > 0) {
                        $difference[$key] = $multidimensionalDiff;
                    }
                }
            } else {
                if (!array_key_exists($key, $array2) || $array2[$key] !== $value) {
                    $difference[$key] = $value;
                }
            }
        }
        return $difference;
    }

    public function multidimensionalSortByKeys(array $array): array
    {
        ksort($array);
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->multidimensionalSortByKeys($value);
            }
        }
        return $array;
    }

    // пока что умеет работать только с десятком $items
    public function getItemByStringEnd(string $string, array $items): string
    {
        $partsQuantity = count($items);
        if ($partsQuantity === 1) {
            return reset($items);
        }
        if ($partsQuantity !== 10) {
            throw new UnexpectedValueException('The number of items must be 10');
        }
        // На 64-битных платформах все результаты crc32() будут положительными целыми.
        $key = mb_substr((string) crc32($string), -1);

        return $items[$key];
    }
}
