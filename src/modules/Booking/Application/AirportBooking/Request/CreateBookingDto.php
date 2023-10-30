<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\Request;

use Carbon\CarbonInterface;
use Module\Shared\Enum\CurrencyEnum;

class CreateBookingDto
{
    public function __construct(
        public readonly int $cityId,
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly CurrencyEnum $currency,
        public readonly int $airportId,
        public readonly int $serviceId,
        public readonly CarbonInterface $date,
        public readonly string $flightNumber,
        public readonly int $creatorId,
        public readonly ?int $orderId,
        public readonly ?string $note = null
    ) {}
}
