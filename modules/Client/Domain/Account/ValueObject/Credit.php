<?php

declare(strict_types=1);

namespace Module\Client\Domain\Account\ValueObject;

final class Credit
{
    public function __construct(
        private readonly float $value,
    ) {
    }

    public function value(): float
    {
        return $this->value;
    }
}