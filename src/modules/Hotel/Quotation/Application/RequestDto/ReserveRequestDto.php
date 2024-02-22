<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\RequestDto;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Dto\BookingRoomDto;

final class ReserveRequestDto
{
    /**
     * @param int $bookingId
     * @param CarbonPeriod $bookingPeriod
     * @param BookingRoomDto[] $bookingRooms
     */
    public function __construct(
        public readonly int $bookingId,
        public readonly int $hotelId,
        public readonly CarbonPeriod $bookingPeriod,
        public readonly array $bookingRooms,
    ) {
    }
}
