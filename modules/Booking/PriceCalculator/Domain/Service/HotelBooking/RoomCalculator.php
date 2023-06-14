<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking;

use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking;
use Module\Booking\Hotel\Domain\ValueObject\RoomPrice;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\FormulaVariables;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\HORoomPriceFormula;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Support\DayPricesCollection;
use Module\Pricing\CurrencyRate\Domain\ValueObject\CurrencyEnum;

class RoomCalculator
{
    public function __construct(
        private readonly RoomBooking $roomBooking,
        private readonly CurrencyEnum $currency,
        private readonly DayPricesCollection $dayPricesCollection,
        private readonly FormulaVariables $variables
    ) {
    }

    public function calculate(): RoomPrice
    {
        $netValue = 0.0;
        $hoValue = 0.0;
        $boValue = 0.0;

        $isResident = $this->roomBooking->details()->isResident();
        $guestsCount = $this->roomBooking->guests()->count();
        $hoFormula = new HORoomPriceFormula($this->variables);
        $boFormula = new HORoomPriceFormula($this->variables);

        foreach ($this->dayPricesCollection as $date) {
            $netValue += $date->netValue;
            $hoValue += $hoFormula->calculate($date->netValue, $isResident, $guestsCount, 1);
            $boValue += $boFormula->calculate($date->netValue, $isResident, $guestsCount, 1);
        }
        $avgDailyValue = $netValue / $this->dayPricesCollection->nightsCount();

        return new RoomPrice(
            $this->currency,
            $netValue,
            $avgDailyValue,
            $hoValue,
            $boValue
        );
    }
}
