<?php
declare(strict_types=1);

namespace YaPro\Helper;

use stdClass;
use function addslashes;
use function implode;
use function is_float;
use function is_numeric;
use function is_string;
use function str_replace;

trait JsonTrait
{
    /**
     * Просто функция json_encode преднастроенная для удобной человеку работы с кирилицей.
     */
    public function encode($value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR );
    }
    /**
     * Стандартная php-функция формирует недостаточно правильный json (добавляя ключ [0] при обработке массива).
     *
     * @param $val
     *
     * @return int|string
     */
    public function jsonEncode($val)
    {
        if (is_string($val)) {
            return '"' .
                str_replace("\t", '\t',
                str_replace("\n", '\n',
                str_replace("\r\n", '\r\n',
                str_replace('"', '\"',
                str_replace('\\', '\\\\', $val))))) . '"';
        }
        if (is_float($val)) {
            // чтобы не писать: PHP_MAJOR_VERSION === 8 ? '0.0' : '0';
            // это подобно json_encode(0.0, JSON_PRESERVE_ZERO_FRACTION)
            return $val === 0.0 ? number_format($val, 1) : $val;
        }
        if (is_numeric($val)) {
            return $val;
        }
        if ($val === null) {
            return 'null';
        }
        if ($val === true) {
            return 'true';
        }
        if ($val === false) {
            return 'false';
        }

        $isObject = gettype($val) === 'object';
        $i = 0;
        foreach ($val as $k => $v) {
            if ($k !== $i++) {
                $isObject = true;
                break;
            }
        }

        $res = [];
        foreach ($val as $k => $v) {
            $v = $this->jsonEncode($v);
            if ($isObject) {
                $v = '"' . addslashes((string) $k) . '"' . ':' . $v;
            }
            $res[] = $v;
        }
        $res = implode(',', $res);

        return $isObject ? '{' . $res . '}' : '[' . $res . ']';
    }

    /**
     * Wrapper for JSON decode that implements error detection with helpful
     * error messages.
     *
     * @param string $json JSON data to parse
     * @param bool $assoc When true, returned objects will be converted
     *                        into associative arrays.
     * @param int $depth User specified recursion depth.
     * @param int $options Bitmask of JSON decode options.
     *
     * @return mixed
     * @throws \InvalidArgumentException if the JSON cannot be parsed.
     * @link http://www.php.net/manual/en/function.json-decode.php
     */
    public function jsonDecode($json, $assoc = false, $depth = 512, $options = 0)
    {
        $data = \json_decode($json, $assoc, $depth, $options);
        if (JSON_ERROR_NONE !== json_last_error()) {
            $message = json_last_error_msg();
            throw new \InvalidArgumentException('json_decode error: ' . ($message === false ? 'Unknown error' : $message));
        }
        return $data;
    }

    /**
     * Сравнивает два JSON-объекта и заменяет совпадающие значения на null (в первом json).
     *
     * @param string $json1 Первый JSON-объект
     * @param string $json2 Второй JSON-объект
     * @param array $safeFields Поля, которые сохраняют свое значение при совпадении
     * @return string $json1-объект с заменёнными значениями
     */
    public function nullifyEqualFields(string $json1, string $json2, array $safeFields = []): string
    {
        // Декодируем JSON в массивы
        $arr1 = $this->jsonDecode($json1, true);
        $arr2 = $this->jsonDecode($json2, true);

        // Рекурсивная обработка массивов
        $this->nullifyMatches($arr1, $arr2, $safeFields);

        // Возвращаем изменённый JSON
        return $this->jsonEncode($arr1);
    }

    /**
     * Рекурсивно проверяет и заменяет совпадающие значения на null.
     *
     * @param array &$arr1 Первый массив (изменяется)
     * @param array $arr2 Второй массив
     * @param array $safeFields Поля, которые сохраняют свое значение при совпадении
     */
    private function nullifyMatches(array &$arr1, array $arr2, array $safeFields = []): void
    {
        foreach ($arr1 as $key => &$value) {
            if (array_key_exists($key, $arr2)) {
                if (is_array($value) && is_array($arr2[$key])) {
                    // Рекурсивно проверяем вложенные массивы
                    $this->nullifyMatches($value, $arr2[$key], $safeFields);
                } elseif ($value === $arr2[$key]) {
                    // Если значения совпадают, заменяем на null (если не сказано сохранить тип)
                    if (!in_array($key, $safeFields, true)) {
                        $value = null;
                    }
                }
            }
        }
    }
}

