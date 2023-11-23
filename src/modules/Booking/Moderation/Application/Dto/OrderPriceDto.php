<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

use Module\Shared\Dto\CurrencyDto;
use Sdk\Module\Foundation\Support\Dto\Dto;

class OrderPriceDto extends Dto
{
    public function __construct(
        public readonly CurrencyDto $currency,
        public readonly float $value,
    ) {}
}
