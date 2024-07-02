<?php
declare(strict_types=1);

namespace YaPro\Helper\Array;

class KeyIncrementKeeper extends AbstractKeeper
{
    public function add($key, $value = null): void
    {
        $this->all[$key] = ($this->all[$key] ?? 0) + 1;
    }

    public function countBy($key): int
    {
        return $this->all[$key] ?? 0;
    }
}
