<?php
declare(strict_types=1);

namespace YaPro\Helper\Arrays;

class KeyKeeper extends AbstractKeeper
{
    public function add($key, $value): void
    {
        $this->all[$key] = $value;
    }
}
