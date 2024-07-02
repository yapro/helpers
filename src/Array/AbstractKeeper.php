<?php
declare(strict_types=1);

namespace YaPro\Helper\Array;

use Iterator;

abstract class AbstractKeeper implements Iterator
{
    protected array $all = [];

    abstract function add($key, $value): void;

    public function count(): int
    {
        return count($this->all);
    }

    public function countBy($key): int
    {
        return isset($this->all[$key]) ? count($this->all[$key]) : 0;
    }

    public function getBy($key): mixed
    {
        return $this->all[$key];
    }

    public function setKeyValue($key, $value): self
    {
        $this->all[$key] = $value;

        return $this;
    }

    public function current(): mixed
    {
        return current($this->all);
    }

    public function next(): mixed
    {
        return next($this->all);
    }

    public function key(): mixed
    {
        return key($this->all);
    }

    public function valid(): bool
    {
        return key($this->all) !== null;
    }

    public function rewind(): mixed
    {
        return reset($this->all);
    }
}
