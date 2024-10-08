<?php
declare(strict_types=1);

namespace YaPro\Helper\Arrays;

class KeyValuesKeeper extends AbstractKeeper
{
    public function add($key, $value = null): void
    {
        if (!isset($this->all[$key])) {
            $this->all[$key] = [];
        }
        $this->all[$key][] = $value;
    }
}
