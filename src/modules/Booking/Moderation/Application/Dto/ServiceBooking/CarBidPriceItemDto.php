<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\ServiceBooking;

use Sdk\Shared\Dto\CurrencyDto;

class CarBidPriceItemDto
{
    public function __construct(
        public readonly CurrencyDto $currency,
        public readonly float $valuePerCar,
        public readonly ?float $manualValuePerCar,
        public readonly float $totalAmount
    ) {}
}
