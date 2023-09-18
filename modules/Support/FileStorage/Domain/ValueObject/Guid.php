<?php

namespace Module\Support\FileStorage\Domain\ValueObject;

class Guid
{
    public function __construct(private readonly string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }
}