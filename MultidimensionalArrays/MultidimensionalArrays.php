<?php

class MultidimensionalArrays
{
    /**
     * @param array $first
     * @param array $second
     * @return array
     */
    public static function diffKeys(array $first, array $second)
    {
        if ($diff = array_diff_key($first, $second)){
            return $diff;
        }else{
            foreach($first as $key => $value){
                if(is_array($value) && $diff = self::diffKeys($value, $second[$key])){
                    return [$key => $diff];
                }
            }
        }
        return [];
    }
}