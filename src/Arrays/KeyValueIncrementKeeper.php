<?php
declare(strict_types=1);

namespace YaPro\Helper\Arrays;

class KeyValueIncrementKeeper extends AbstractKeeper
{
    public function add($key, $value): void
    {
        if (!isset($this->all[$key])) {
            $this->all[$key] = [];
        }
        $this->all[$key][$value] = ($this->all[$key][$value] ?? 0) + 1;
    }

    public function countByKeyValue($key, $value): int
    {
        return $this->all[$key][$value] ?? 0;
    }
}
