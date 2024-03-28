<?php

namespace Pkg\Supplier\Traveline\Dto\Reservation;

use Pkg\Supplier\Traveline\Dto\Reservation\Room\GuestDto;
use Pkg\Supplier\Traveline\Dto\Reservation\Room\TotalDto;

class RoomStayDto
{
    public function __construct(
        public readonly int $roomTypeId,

        public readonly int $ratePlanId,

        /** @var GuestDto[] $guests */
        public readonly array $guests,

        public readonly int $adults,

        public readonly ?array $bookingPerDayPrices = null,

        public readonly TotalDto $total,

        public readonly ?string $guestComment = null,

        public readonly int $children = 0,
        public readonly int $commission = 0,
    ) {}
}
