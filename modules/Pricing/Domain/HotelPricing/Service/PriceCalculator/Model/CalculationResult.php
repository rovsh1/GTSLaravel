<?php

namespace Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Model;

class CalculationResult
{
    public function __construct(
        public readonly float $value,
        public readonly string $notes,
    ) {
    }
}
