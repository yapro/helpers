<?php
declare(strict_types = 1);

namespace YaPro\Helper\Arrays;

use ArrayIterator;
use InfiniteIterator;
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
     * Возвращает то, чем $array1 отличается от $array2. Если нужно наоборот, то поменяй порядок передаваемых массивов.
     * Если нужно найти отличие обоих массивов, вызови метод 2 раза передавая массивы в разном порядке.
     * 
     * ВАЖНО: функция не учитывает в каком порядке ключи (иногда это плюс, иногда минус)
     * 
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

    public function getItemsByStringEnd(string $string, array $itemsList): string
    {
        $result = [];
        $keys = count($itemsList);
        foreach ($itemsList as $item) {
            $result[] = $this->getItemByStringEnd($string, $item);
            $string = mb_substr($string, 0, -1);
        }
        
        return implode(' ', $result);
    }

    /**
     * Example: getIntegerTail($userId, count($users)) or getIntegerTail($name, count($messages))
     * 
     * @param int|string $value
     * @param int $quantity
     * @return int
     */
    public function getIntegerTail($value, int $quantity): int
    {
        if (is_numeric($value)) {
            $value = (string) $value;
        }
        // На 64-битных платформах все результаты crc32() будут положительными целыми.
        return (int) mb_substr((string) crc32((string) $value), -mb_strlen((string) $quantity));
    }

    /**
     * Example:
     * $userIdentity = $this->getIntegerTail($userName, count($users));
     * $permanentUserChoise = $this->getIteration($userIdentity, $cars);
     * 
     * @param int $necessaryIteration
     * @param array $items
     * @return mixed
     */
    public function getIteration(int $necessaryIteration, array $items)
    {
        $iterator = new InfiniteIterator(new ArrayIterator($items));
        $iterationNumber = 0;
        foreach ($iterator as $item) {
            if ($iterationNumber === $necessaryIteration) {
                return $item;
            }
            $iterationNumber++;
        }

        return null;
    }

    public function fill(array &$array)
    {
        $argList = func_get_args();
        array_shift($argList); // &$array removing
        $this->arrayMagic('set', $array, ...$argList);
    }

    public function push(array &$array)
    {
        $argList = func_get_args();
        array_shift($argList); // &$array removing
        $this->arrayMagic('push', $array, ...$argList);
    }

    public function arrayMagic(string $mode, array &$array)
    {
        $argList = func_get_args();
        array_shift($argList); // $mode removing
        array_shift($argList); // &$array removing
        if (empty($argList)) {
            return;
        }
        $value = array_pop($argList);

        $current = &$array;
        foreach ($argList as $key) {
            // &$current[$key] не возвращает значение, а создает ключ и возвращает ссылку на значение null, при этом, 
            // т.к. обращение идет к null по ключу, то PHP создает массив с ключем (магия), а если значение есть, то
            // возвращается ссылка на это значение (тонкая магия, может поломается в одной из следующих версий PHP) 
            $current = &$current[$key];
        }
        if ($mode === 'push') {
            if (is_null($current) || is_array($current)) {
                $current[] = $value;
                return;
            }
            // Происходит попытка добавить значение в массив, в котором $current ссылается например на строку:
            throw new UnexpectedValueException('Auto-conversion to an array is not supported');
        }
        $current = $value;
    }

    // Пример использования
    // $array = [1, '2', '003', 'hello', 4.5, '4.5', 6, null, true, false];
    // $filtered = filterIntegers($array);
    function filterIntegers(array $input): array {
        $result = [];

        foreach ($input as $value) {
            // Проверка: значение числовое, не bool, и без дробной части
            if (is_numeric($value) && !is_bool($value) && (int)$value == $value) {
                $result[] = (int)$value;
            }
        }

        return $result;
    }
}
