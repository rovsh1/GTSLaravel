<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Response;

use Module\Shared\Application\Dto\CurrencyDto;
use Sdk\Module\Foundation\Support\Dto\Dto;

class PriceItemDto extends Dto
{
    public function __construct(
        public readonly CurrencyDto $currency,
        public readonly float $calculatedValue,
        public readonly ?float $manualValue,
        public readonly ?float $penaltyValue,
        public readonly bool $isManual,
    ) {}
}
