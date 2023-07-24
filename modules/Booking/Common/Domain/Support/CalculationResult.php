<?php

namespace Module\Booking\Common\Domain\Support;

class CalculationResult
{
    public function __construct(
        public readonly float $value,
        public readonly string $notes,
    ) {
    }
}
