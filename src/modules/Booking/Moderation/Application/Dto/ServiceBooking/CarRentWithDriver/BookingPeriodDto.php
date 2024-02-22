<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\ServiceBooking\CarRentWithDriver;

class BookingPeriodDto
{
    public function __construct(
        public readonly string $dateFrom,
        public readonly string $dateTo,
    ) {}
}
