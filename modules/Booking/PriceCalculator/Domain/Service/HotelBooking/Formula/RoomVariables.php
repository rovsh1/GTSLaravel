<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula;

class RoomVariables
{
    public function __construct(
        public readonly bool $isResident,
        public readonly int $guestsCount,
        public readonly int $nightsCount
    ) {
    }
}
