<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\Dto;

use Sdk\Shared\Dto\MoneyDto;

class OrderDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $clientId,
        public readonly MoneyDto $clientPrice,
        public readonly MoneyDto $payedAmount,
        public readonly MoneyDto $remainingAmount,
    ) {}
}
