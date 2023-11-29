<?php

declare(strict_types=1);

namespace Module\Client\Payment\Application\Dto;

use Sdk\Shared\Dto\MoneyDto;

class PaymentDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $clientId,
        public readonly MoneyDto $totalAmount,
        public readonly MoneyDto $payedAmount,
        public readonly MoneyDto $remainingAmount,
    ) {}
}
