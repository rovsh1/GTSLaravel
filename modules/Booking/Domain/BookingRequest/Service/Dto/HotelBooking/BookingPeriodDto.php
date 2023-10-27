<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Dto\HotelBooking;

class BookingPeriodDto
{
    public function __construct(
        public readonly string $startDate,
        public readonly string $endDate,
        public readonly int $nightsCount
    ) {}
}
