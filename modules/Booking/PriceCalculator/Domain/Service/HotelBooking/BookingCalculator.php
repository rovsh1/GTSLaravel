<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking;

use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\FormulaVariables;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyEnum;

class BookingCalculator
{
    public function __construct(
        private readonly CurrencyEnum $currency,
        private readonly FormulaVariables $variables
    ) {
    }

    public function calculate(): BookingPrice
    {
        foreach ($rooms as $room) {
            $roomPrice = (new RoomCalculator())->calculate();
        }

        return new BookingPrice(
            $this->currency,
            $netValue,
            $hoValue,
            $boValue
        );
    }
}
