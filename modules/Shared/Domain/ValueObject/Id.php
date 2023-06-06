<?php

declare(strict_types=1);

namespace Module\Shared\Domain\ValueObject;

class Id implements ValueObjectInterface
{
    public function __construct(
        private readonly int $value
    ) {}

    public function value(): int
    {
        return $this->value;
    }
}
