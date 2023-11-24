<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

use Module\Shared\Dto\CurrencyDto;

class ProfitItemDto
{
    public function __construct(
        public readonly CurrencyDto $currency,
        public readonly float $supplierValue,
        public readonly float $clientValue,
        public readonly float $profitValue,
    ) {}
}
