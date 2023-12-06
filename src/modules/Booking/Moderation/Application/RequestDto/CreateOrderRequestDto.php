<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\RequestDto;

use Carbon\CarbonPeriod;
use Sdk\Shared\Enum\CurrencyEnum;

class CreateOrderRequestDto
{
    public function __construct(
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly CurrencyEnum $currency,
        public readonly int $administratorId,
        public readonly int $creatorId,
    ) {}
}
