<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\Service;

class PriceDto
{
    public function __construct(
        public readonly int $quantity,
        public readonly float $amount,
        public readonly float $total,
        public readonly string $currency,
        public readonly ?float $penalty,
    ) {}
}
