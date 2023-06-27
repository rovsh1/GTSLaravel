<?php

namespace Module\Booking\PriceCalculator\Domain\ValueObject;

class CalculationResult
{
    public function __construct(
        public readonly float $value,
        public readonly string $notes,
    ) {
    }
}
