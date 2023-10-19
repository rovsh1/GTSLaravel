<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking;

class BookingDto
{
    public function __construct(
        public readonly string $number,
        public readonly string $status,
        public readonly HotelInfoDto $hotel,
        public readonly BookingPeriodDto $period,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}
}
