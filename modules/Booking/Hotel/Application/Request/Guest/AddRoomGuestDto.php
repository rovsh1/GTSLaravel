<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Request\Guest;

class AddRoomGuestDto
{
    public function __construct(
        public readonly int $bookingId,
        public readonly int $roomBookingId,
        public readonly string $fullName,
        public readonly int $countryId,
        public readonly int $gender,
        public readonly bool $isAdult,
        public readonly ?int $age
    ) {}
}
