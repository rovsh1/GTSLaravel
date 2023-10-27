<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Service\Dto\TransferBooking;

class BookingPeriodDto
{
    public function __construct(
        public readonly string $startDate,
        public readonly string $startTime,
        public readonly string $endDate,
        public readonly string $endTime,
        public readonly ?int $countDays,
    ) {}
}