<?php
declare(strict_types=1);

namespace YaPro\Helper;

use function addslashes;
use function implode;
use function is_float;
use function is_numeric;
use function is_string;
use function str_replace;

class JsonHelper
{
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
            return '"' . str_replace('\\\n', '\n', addslashes(str_replace(PHP_EOL, '\n', $val))) . '"';
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

        $assoc = false;
        $i = 0;
        foreach ($val as $k => $v) {
            if ($k !== $i++) {
                $assoc = true;
                break;
            }
        }

        $res = [];
        foreach ($val as $k => $v) {
            $v = $this->jsonEncode($v);
            if (is_string($k)) {
                $v = '"' . addslashes($k) . '"' . ':' . $v;
            }
            $res[] = $v;
        }
        $res = implode(',', $res);

        return ($assoc) ? '{' . $res . '}' : '[' . $res . ']';
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
}

