<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBooking;


class RoomDayPriceDto
{
    public function __construct(
        public readonly string $date,
        public readonly int|float $baseValue,
        public readonly int|float $grossValue,
        public readonly int|float $netValue,
        public readonly string $grossFormula,
        public readonly string $netFormula
    ) {}
}
