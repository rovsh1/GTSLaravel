<?php

namespace Module\Booking\Hotel\Domain\ValueObject;

class RoomPrice
{
    public function __construct(
        private readonly float $netValue,
        private readonly float $avgDailyValue,
        private readonly float $hotelValue,
        private readonly float $clientValue
    ) {
    }
}
