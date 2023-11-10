<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking;

class PriceDto
{
    public function __construct(
        public readonly float $amount,
        public readonly string $currency,
    ) {}
}
