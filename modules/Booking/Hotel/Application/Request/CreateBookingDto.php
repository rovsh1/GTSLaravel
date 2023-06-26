<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Request;

use Carbon\CarbonPeriod;

class CreateBookingDto
{
    public function __construct(
        public readonly int $cityId,
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly int $hotelId,
        public readonly CarbonPeriod $period,
        public readonly int $creatorId,
        public readonly ?int $orderId,
        public readonly ?string $note = null
    ) {}
}
