<?php
declare(strict_types = 1);

namespace YaPro\Helper;

class ArrayHelper
{
    /**
     * Computes the difference of multidimensional arrays using keys for comparison.
     * @param array $first
     * @param array $second
     * @return array
     */
    public function multidimensionalDiffByKeys(array $first, array $second)
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
}