<?php
declare(strict_types = 1);

namespace YaPro\Helper\Validation;

use function explode;
use function filter_var;
use function in_array;
use function is_bool;
use function is_string;
use function preg_match;
use function strpos;
use const FILTER_VALIDATE_FLOAT;
use const FILTER_VALIDATE_INT;

class ScalarValidator
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function getBoolean($value): bool
    {
        $filteredValue = $this->filterBoolean($value);
        if ($filteredValue === null) {
            throw new \UnexpectedValueException('Value is not boolean');
        }

        return $filteredValue;
    }

    /**
     * Аналог php-функции is_numeric(), но умеет работать числами больше чем PHP_INT_MAX и в случае если это не число,
     * выбрасывает исключение.
     *
     * @param mixed $value
     * @param array $allowedCharacters
     *
     * @return int
     *
     * @throws \UnexpectedValueException
     */
    public function getInteger($value, array $allowedCharacters = []): int
    {
        return $this->filterInteger($value, $allowedCharacters);
    }

    /**
     * @param mixed $value
     * @param array $allowedCharacters
     *
     * @throws \UnexpectedValueException
     *
     * @return float
     */
    public function getFloat($value, array $allowedCharacters = []): float
    {
        return $this->filterFloat($value, $allowedCharacters, true);
    }

    /**
     * @param $value
     *
     * @return bool|null
     */
    public function filterBoolean($value): ?bool
    {
        if (is_bool($value)) {
            return $value;
        }
        if (is_string($value)) {
            if ($value === 'true') {
                return true;
            }
            if ($value === 'false') {
                return false;
            }
        }

        return null;
    }

    /**
     * Аналог php-функции is_numeric(), но умеет работать с числами больше чем PHP_INT_MAX и в случае, если это не число
     * или число с экспоненциальной частью (пример: +0123.45e6), функция возвращает null.
     *
     * @param mixed $value
     * @param array $allowedCharacters
     *
     * @return int|null
     */
    public function filterInteger($value, array $allowedCharacters = []): ?int
    {
        $int = filter_var($value, FILTER_VALIDATE_INT);
        if ($int !== false) {
            return $int;
        }
        if (!empty($allowedCharacters) && in_array($value, $allowedCharacters, true)) {
            return 0;
        }
        if (is_string($value) && preg_match('/^[+-]{0,1}\d+$/', $value) === 1) {
            // преобразуем строку в число
            return $value * 1;
        }

        return null;
    }

    /**
     * @param mixed      $value
     * @param array      $allowedCharacters
     * @param false|bool $throwException
     *
     * @return float|null
     *
     * @throws \TypeError
     * @codeCoverageIgnore
     */
    public function filterFloat($value, array $allowedCharacters = [], bool $throwException = false): ?float
    {
        if (!empty($allowedCharacters) && in_array($value, $allowedCharacters, true)) {
            return 0.0;
        }

        if (is_string($value) && $value !== '0') {
            $explodedValue = explode('.', $value);
            $leftPart = $explodedValue[0];
            // Если строка начинается с нуля
            if (strpos($leftPart, '0') === 0 && $leftPart !== '0') { // check string '01.2'
                if ($throwException) {
                    throw new \TypeError('Value must be of the type float, string given');
                }

                return null;
            }
        }

        $filteredValue = filter_var($value, FILTER_VALIDATE_FLOAT);
        if ($filteredValue === false) {
            if ($throwException) {
                throw new \TypeError('Value is not float');
            }

            return null;
        }

        return $filteredValue;
    }

    /**
     * @param string $pattern
     * @return bool
     */
    public function isPatternValid($pattern)
    {
        set_error_handler(function() {}, E_WARNING);
        $isValid = preg_match($pattern, '') !== false;
        restore_error_handler();
        return $isValid;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getArrayWithIntegerValues(array $data)
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->getInteger($value);
        }

        return $data;
    }
}
