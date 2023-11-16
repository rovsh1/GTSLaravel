<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\ResponseDto;

use Carbon\CarbonInterface;

class OrderBookingPeriodDto
{
    public function __construct(
        public readonly CarbonInterface $dateFrom,
        public readonly CarbonInterface $dateTo,
    ) {}
}
