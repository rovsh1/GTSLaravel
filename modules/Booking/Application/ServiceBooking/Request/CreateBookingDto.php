<?php

declare(strict_types=1);

namespace Module\Booking\Application\ServiceBooking\Request;

class CreateBookingDto
{
    public function __construct(
        public readonly int $cityId,
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly int $currencyId,
        public readonly int $serviceId,
        public readonly int $creatorId,
        public readonly ?int $orderId,
        public readonly ?string $note = null
    ) {}
}
