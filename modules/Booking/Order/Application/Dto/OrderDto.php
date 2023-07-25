<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\Dto;

use Carbon\CarbonImmutable;
use Module\Shared\Application\Dto\CurrencyDto;
use Sdk\Module\Foundation\Support\Dto\Dto;

class OrderDto extends Dto
{
    public function __construct(
        public readonly int $id,
        public readonly CurrencyDto $currency,
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly CarbonImmutable $createdAt
    ) {}
}
