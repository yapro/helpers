<?php

declare(strict_types=1);

namespace YaPro\Helper\Iteration;

class RecursiveGeneratorIterator implements \RecursiveIterator
{
    private \Generator $generator;

    public function __construct(\Generator $generator)
    {
        $this->generator = $generator;
    }

    public function current()
    {
        return $this->generator->current();
    }

    public function next()
    {
        $this->generator->next();
    }

    public function key()
    {
        return $this->generator->key();
    }

    public function valid(): bool
    {
        return $this->generator->valid();
    }

    public function rewind()
    {
        $this->generator->rewind();
    }

    public function hasChildren(): bool
    {
        return $this->current() instanceof \Generator;
    }

    public function getChildren(): \RecursiveIterator
    {
        return new RecursiveGeneratorIterator($this->current());
    }
}
