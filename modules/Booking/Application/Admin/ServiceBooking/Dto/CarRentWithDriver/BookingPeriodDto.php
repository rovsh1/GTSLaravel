<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Dto\CarRentWithDriver;

class BookingPeriodDto
{
    public function __construct(
        public readonly string $dateFrom,
        public readonly string $dateTo,
    ) {}
}
