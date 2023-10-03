<?php

namespace Module\Booking\Domain\HotelBooking\Service\PriceCalculator\Model;

class CalculationResult
{
    public function __construct(
        public readonly float $value,
        public readonly string $notes,
    ) {
    }
}
