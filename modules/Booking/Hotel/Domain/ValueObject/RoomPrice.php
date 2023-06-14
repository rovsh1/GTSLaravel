<?php

namespace Module\Booking\Hotel\Domain\ValueObject;


use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyEnum;

class RoomPrice
{
    public function __construct(
        private readonly CurrencyEnum $currency,
        private readonly float $netValue,
        private readonly float $avgDailyValue,
        private readonly float $hotelValue,
        private readonly float $clientValue
    ) {
    }
}
