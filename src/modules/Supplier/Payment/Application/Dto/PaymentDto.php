<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Application\Dto;

use Sdk\Shared\Dto\MoneyDto;

class PaymentDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $supplierId,
        public readonly MoneyDto $totalAmount,
        public readonly MoneyDto $payedAmount,
        public readonly MoneyDto $remainingAmount,
        public readonly array $landings,
    ) {}
}
