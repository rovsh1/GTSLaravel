<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support;

class FormulaVariables
{
    public function __construct(
        public readonly bool $isResident,
        public readonly int $guestsCount,
        public readonly int $vatPercent,
        public readonly int $clientMarkupPercent,
        public readonly float $touristTax,
        public readonly ?int $earlyCheckInPercent,
        public readonly ?int $lateCheckOutPercent
    ) {
    }
}
