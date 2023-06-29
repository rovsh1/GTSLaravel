<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject;

class ManualChangablePrice
{
    public function __construct(
        private readonly float $value,
        private readonly bool $isManual = false
    ) {}

    public function value(): float
    {
        return $this->value;
    }

    public function isManual(): bool
    {
        return $this->isManual;
    }
}
