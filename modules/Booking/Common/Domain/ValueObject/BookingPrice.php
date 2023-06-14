<?php

namespace Module\Booking\Common\Domain\ValueObject;


use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyEnum;

class BookingPrice
{
    public function __construct(
        private readonly CurrencyEnum $currency,
        private readonly float $netValue,
        private readonly float $hotelValue,
        private readonly float $clientValue
    ) {
    }
}
