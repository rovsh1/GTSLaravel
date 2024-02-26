<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Application\Dto;

use Sdk\Shared\Dto\MoneyDto;

class BookingDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $supplierId,
        public readonly MoneyDto $supplierPrice,
        public readonly ?MoneyDto $supplierPenalty,
        public readonly MoneyDto $payedAmount,
        public readonly MoneyDto $remainingAmount,
    ) {}
}
