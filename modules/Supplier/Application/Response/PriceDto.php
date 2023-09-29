<?php

declare(strict_types=1);

namespace Module\Supplier\Application\Response;

use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Foundation\Support\Dto\Dto;

class PriceDto extends Dto
{
    public function __construct(
        public readonly float $amount,
        public readonly CurrencyEnum $currency,
    ) {}
}
