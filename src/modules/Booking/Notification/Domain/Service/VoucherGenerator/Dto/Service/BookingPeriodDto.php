<?php

declare(strict_types=1);

namespace Module\Booking\Notification\Domain\Service\VoucherGenerator\Dto\Service;

class BookingPeriodDto
{
    public function __construct(
        public readonly string $startDate,
        public readonly string $startTime,
        public readonly ?string $endDate,
        public readonly ?string $endTime,
        public readonly ?int $countDays,
    ) {}
}
