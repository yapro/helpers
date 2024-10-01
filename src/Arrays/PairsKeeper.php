<?php
declare(strict_types=1);

namespace YaPro\Helper\Arrays;

class PairsKeeper extends AbstractKeeper
{
    public function add($key, $value = null): void
    {
        $this->all[] = new Pair($key, $value);
    }

    /**
     * @return Pair[]
     */
    public function getAll(): array
    {
        return $this->all;
    }
}
