<?php
declare(strict_types = 1);

namespace YaPro\Helper;

final class CsvHelper
{
    /**
     * @param string $filePath
     * @return array
     */
    public function getArrayWithNumbersFromFile(string $filePath): array
    {
        $rows = $this->getCsvContent($filePath);
        $fieldsNames = array_shift($rows);
        $rows = $this->getRowsWithFloatOrIntegerValue($rows);
        $fieldsTypes = $this->getFieldsTypes($rows);
        $rows = $this->getFixedRows($fieldsNames, $fieldsTypes, $rows);

        return $rows;
    }

    /**
     * @param array $data
     * @return string
     */
    public function formatToString(array $data): string
    {
        $stream = fopen('php://memory', 'wb');

        try {
            foreach ($data as $row) {
                fputcsv($stream, $row);
            }

            rewind($stream);

            return stream_get_contents($stream);
        } finally {
            fclose($stream);
        }
    }

    /**
     * @param string $fileName Имя файла
     * @return array
     */
    private function getCsvContent(string $fileName): array
    {
        $rows = [];
        $file = new \SplFileObject($fileName, "r");
        while (!$file->eof()) {
            $rows[] = $file->fgetcsv();
        }
        $latestRow = count($rows) - 1;
        if ($latestRow === 0) {
            return $rows;
        }
        // removing latest row if that row is empty
        $previousRow = $latestRow - 1;
        if (count($rows[$latestRow]) !== count($rows[$previousRow])) {
            unset($rows[$latestRow]);
        }
        return $rows;
    }

    /**
     * @param array $fieldsNames
     * @param array $rowTypes
     * @param array $rows
     * @return array
     */
    private function getFixedRows(array $fieldsNames, array $rowTypes, array $rows): array
    {
        foreach ($rows as $k => $row) {
            foreach ($row as $i => $value) {
                settype($value, $rowTypes[$i]);
                $fieldName = $fieldsNames[$i];
                $rows[$k][$fieldName] = $value;
                unset($rows[$k][$i]);
            }
        }

        return $rows;
    }

    /**
     * @param array $rows
     * @return array
     */
    private function getFieldsTypes(array $rows): array
    {
        $rowTypes = [];
        foreach ($rows as $row) {
            foreach ($row as $i => $value) {
                if (array_key_exists($i, $rowTypes)) {
                    $valueType = gettype($value);
                    if ($rowTypes[$i] === 'integer' && $valueType === 'double') {
                        $rowTypes[$i] = $valueType;
                    }
                } else {
                    $rowTypes[$i] = gettype($value);
                }
            }
        }

        return $rowTypes;
    }

    /**
     * @param array $rows
     * @return array
     */
    private function getRowsWithFloatOrIntegerValue(array $rows): array
    {
        foreach ($rows as $k => $row) {
            foreach ($row as $i => $value) {
                $rows[$k][$i] = $this->getFloatOrIntegerValue($value);
            }
        }
        return $rows;
    }

    /**
     * @param $value
     * @return int|float
     */
    private function getFloatOrIntegerValue($value)
    {
        $value = count(explode(',', $value)) === 2 || count(explode('.', $value)) === 2
            ? filter_var(str_replace(',', '.', $value), FILTER_VALIDATE_FLOAT)
            : filter_var($value, FILTER_VALIDATE_INT);
        if ($value === false) {
            throw new \UnexpectedValueException('Value must be only integer or float');
        }
        return $value;
    }
}
