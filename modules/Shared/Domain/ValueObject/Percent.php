<?php

declare(strict_types=1);

namespace Module\Shared\Domain\ValueObject;

class Percent implements ValueObjectInterface
{
    public function __construct(private int $value) {}

    public function value(): int
    {
        return $this->value;
    }
}
