<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking;

use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;

class CalculateVariables
{
    public function __construct(
        public readonly BookingPeriod $bookingPeriod,
        public readonly int $roomId,
        public readonly bool $isResident,
        public readonly int $guestsCount,
        public readonly int $vatPercent,
        public readonly int $clientMarkupPercent,
        public readonly int $touristTax,
        public readonly ?int $earlyCheckInPercent,
        public readonly ?int $lateCheckOutPercent,
    ) {
    }
}
