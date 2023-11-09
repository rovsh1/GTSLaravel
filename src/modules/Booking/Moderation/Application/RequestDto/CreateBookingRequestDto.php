<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\RequestDto;

use Module\Shared\Enum\CurrencyEnum;

class CreateBookingRequestDto
{
    public function __construct(
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly CurrencyEnum $currency,
        public readonly int $serviceId,
        public readonly int $administratorId,
        public readonly int $creatorId,
        public readonly ?int $orderId,
        public readonly ?array $detailsData,
        public readonly ?string $note = null,
    ) {}
}
