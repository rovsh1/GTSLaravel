<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Request;

use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\ServiceTypeEnum;

class CreateBookingDto
{
    public function __construct(
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly CurrencyEnum $currency,
        public readonly ServiceTypeEnum $serviceType,
        public readonly int $creatorId,
        public readonly ?int $orderId,
        public readonly ?string $note = null
    ) {}
}
