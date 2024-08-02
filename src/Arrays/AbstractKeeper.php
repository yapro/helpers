<?php
declare(strict_types=1);

namespace YaPro\Helper\Arrays;

use ArrayAccess;
use Iterator;

abstract class AbstractKeeper implements Iterator, ArrayAccess
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

    public function offsetSet($offset, $value): void {
        if (is_null($offset)) {
            $this->all[] = $value;
        } else {
            $this->all[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool {
        return isset($this->all[$offset]);
    }

    public function offsetUnset($offset): void {
        unset($this->all[$offset]);
    }

    public function offsetGet($offset): mixed {
        return isset($this->all[$offset]) ? $this->all[$offset] : null;
    }
}
