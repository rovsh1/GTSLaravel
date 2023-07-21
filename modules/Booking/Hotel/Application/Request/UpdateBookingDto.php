<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Request;

use Carbon\CarbonPeriod;

class UpdateBookingDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly int $currencyId,
        public readonly CarbonPeriod $period,
        public readonly ?string $note = null
    ) {}
}
