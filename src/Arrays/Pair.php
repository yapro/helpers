<?php
declare(strict_types=1);

namespace YaPro\Helper\Arrays;

readonly class Pair
{
    public function __construct(private string $key, private mixed $value = null)
    {
    }
    
    public function getKey(): string
    {
        return $this->key;
    }
    
    public function getValue(): mixed
    {
        return $this->value;
    }
}
