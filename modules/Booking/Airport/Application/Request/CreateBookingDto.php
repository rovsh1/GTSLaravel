<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\Request;

use Carbon\CarbonInterface;

class CreateBookingDto
{
    public function __construct(
        public readonly int $cityId,
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly int $currencyId,
        public readonly int $airportId,
        public readonly int $serviceId,
        public readonly CarbonInterface $date,
        public readonly int $creatorId,
        public readonly ?int $orderId,
        public readonly ?string $note = null
    ) {}
}
