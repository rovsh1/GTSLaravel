<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Model;

use DateTimeInterface;

class FormulaVariables
{
    public function __construct(
        public readonly bool $isResident,
        public readonly int $guestsCount,
        public readonly int $vatPercent,
        public readonly int $clientMarkupPercent,
        public readonly float $touristTax,
        public readonly DateTimeInterface $startDate,
        public readonly DateTimeInterface $endDate,
        public readonly ?int $earlyCheckInPercent,
        public readonly ?int $lateCheckOutPercent
    ) {
    }
}
