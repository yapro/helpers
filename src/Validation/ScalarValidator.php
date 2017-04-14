<?php
declare(strict_types = 1);

namespace YaPro\Helper\Validation;

/**
 * Class ScalarValidator
 * @package com\calltouch\api\utility
 */
final class ScalarValidator
{
    /**
     * @param array $data
     * @return array
     */
    public static function getArrayWithIntegerValues(array $data): array
    {
        foreach ($data as $key => $value) {
            $numeric = filter_var($value, FILTER_VALIDATE_INT);
            if ($numeric === false) {
                throw new \UnexpectedValueException('Wrong integer value');
            }
            $data[$key] = $numeric;
        }
        return $data;
    }

    /**
     * @param mixed $value
     * @param array $allowedCharacters
     * @return int
     * @throws \UnexpectedValueException
     */
    public static function getInteger($value, array $allowedCharacters = []): int
    {
        if (!empty($allowedCharacters) && in_array($value, $allowedCharacters)) {
            $value = 0;
        } else {
            $value = filter_var($value, FILTER_VALIDATE_INT);
            if ($value === false) {
                throw new \UnexpectedValueException(
                    'Value must be integer ' . ($allowedCharacters ? ' or ' . print_r($allowedCharacters, true) : '')
                );
            }
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return float
     * @throws \UnexpectedValueException
     */
    public static function getFloat($value): float
    {
        $value = filter_var($value, FILTER_VALIDATE_FLOAT);
        if ($value === false) {
            throw new \UnexpectedValueException('Value must be float');
        }

        return $value;
    }
}