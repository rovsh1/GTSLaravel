<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\Request\Guest;

class UpdateRoomGuestDto
{
    public function __construct(
        public readonly int $bookingId,
        public readonly int $roomBookingId,
        public readonly int $guestIndex,
        public readonly string $fullName,
        public readonly int $countryId,
        public readonly int $gender,
        public readonly bool $isAdult,
        public readonly ?int $age
    ) {}
}
