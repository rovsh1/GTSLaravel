<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto;

use Module\Shared\Dto\CurrencyDto;
use Sdk\Module\Foundation\Support\Dto\Dto;

class ProfitItemDto extends Dto
{
    public function __construct(
        public readonly CurrencyDto $currency,
        public readonly float $supplierValue,
        public readonly float $clientValue,
        public readonly float $profitValue,
    ) {}
}
