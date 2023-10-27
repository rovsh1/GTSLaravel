<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking;

class HotelInfoDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $phone,
        public readonly string $city,
    ) {}
}