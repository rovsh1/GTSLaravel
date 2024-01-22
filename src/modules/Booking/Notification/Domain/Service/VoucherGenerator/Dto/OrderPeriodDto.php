<?php

declare(strict_types=1);

namespace Module\Booking\Notification\Domain\Service\VoucherGenerator\Dto;

class OrderPeriodDto
{
    public function __construct(
        public readonly string $dateFrom,
        public readonly string $dateTo,
    ) {}
}
