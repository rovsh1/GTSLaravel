<?php

namespace Module\Integration\Traveline\Application\Dto\Reservation\Room;

class DayPriceDto
{
    public function __construct(
        public readonly string $dateYmd,
        public readonly float  $price
    ) {}
}
