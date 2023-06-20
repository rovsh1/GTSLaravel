<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking;

class CalculateVariables
{
    public function __construct(
        public readonly int $bookingId,
        public readonly int $roomId,
        public readonly bool $isResident,
        public readonly int $guestsCount,
        public readonly ?int $earlyCheckInPercent,
        public readonly ?int $lateCheckOutPercent,
    ) {
    }
}
