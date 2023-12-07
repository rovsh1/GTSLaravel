<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\Service\Dto\HotelBooking;

use Module\Booking\Requesting\Domain\Service\Dto\GuestDto;

class RoomDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $rate,
        public readonly string $checkInTime,
        public readonly string $checkOutTime,
        /** @var GuestDto[] */
        public readonly array $guests,
        public readonly string|null $note,
    ) {}
}
