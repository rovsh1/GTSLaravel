<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Domain\Booking\Service\PriceCalculator\Model;

class ServicePrice
{
    public function __construct(
        public readonly float $netPrice,
        public readonly float $grossPrice,
    ) {}
}
