<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

use Module\Shared\Dto\CurrencyDto;

final class PriceItemDto
{
    public function __construct(
        public readonly CurrencyDto $currency,
        public readonly float $calculatedValue,
        public readonly ?float $manualValue,
        public readonly ?float $penaltyValue,
        public readonly bool $isManual,
    ) {}
}
