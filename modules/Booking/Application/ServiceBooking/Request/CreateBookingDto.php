<?php

declare(strict_types=1);

namespace Module\Booking\Application\ServiceBooking\Request;

use Module\Shared\Enum\CurrencyEnum;

class CreateBookingDto
{
    public function __construct(
        public readonly int $cityId,
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly CurrencyEnum $currency,
        public readonly int $serviceId,
        public readonly int $creatorId,
        public readonly ?int $orderId,
        public readonly ?string $note = null
    ) {}
}
