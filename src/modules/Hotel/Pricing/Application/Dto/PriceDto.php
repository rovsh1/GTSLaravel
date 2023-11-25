<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Application\Dto;

use Sdk\Shared\Enum\CurrencyEnum;

final class PriceDto
{
    public function __construct(
        public readonly float $amount,
        public readonly CurrencyEnum $currency,
    ) {}
}
