<?php
declare(strict_types = 1);

namespace YaPro\Helper\Validation;

/**
 * Class ScalarValidator
 */
class ScalarValidator
{
    /**
     * @param array $data
     * @return array
     * @throws \TypeError
     */
    public function getArrayWithIntegerValues(array $data): array
    {
        foreach ($data as $key => $value) {
            $numeric = filter_var($value, FILTER_VALIDATE_INT);
            if ($numeric === false) {
                throw new \TypeError('Wrong integer value');
            }
            $data[$key] = $numeric;
        }
        return $data;
    }

    /**
     * @param mixed $value
     * @param array $allowedCharacters
     * @return int
     * @throws \TypeError
     */
    public static function getInteger($value, array $allowedCharacters = []): int
    {
        if (!empty($allowedCharacters) && in_array($value, $allowedCharacters)) {
            return 0;
        }
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    /**
     * @param mixed $value
     * @return float
     * @throws \TypeError
     */
    public function getFloat($value): float
    {
        if (is_string($value)) {
            $leftPart = explode('.', $value);// check string '01.2'
            if (strlen($leftPart[0]) > 1) {
                throw new \TypeError('Value must be of the type float, string given');
            }
        }
        return filter_var($value, FILTER_VALIDATE_FLOAT);
    }

    /**
     * @param string $pattern
     * @return bool
     */
    function isPatternValid($pattern)
    {
        set_error_handler(function() {}, E_WARNING);
        $isValid = preg_match($pattern, '') !== false;
        restore_error_handler();
        return $isValid;
    }
}