<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Dto\Details\RoomBooking;

final class RoomPriceDto
{
    public function __construct(
        public readonly ?float $grossDayValue,
        public readonly ?float $netDayValue,
        /** @var RoomDayPriceDto[] $dayPrices */
        public readonly array $dayPrices,
        public readonly int|float $grossValue,
        public readonly int|float $netValue,
    ) {
    }
}
