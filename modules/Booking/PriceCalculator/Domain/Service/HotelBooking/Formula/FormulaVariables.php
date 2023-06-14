<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula;

abstract class FormulaVariables
{
    public function __construct(
        public readonly int $vatPercent,
        public readonly int $clientMarkupPercent,
        public readonly int $conditionalMarkupPercent,
        public readonly int $touristTax
    ) {
    }

    /**
     * T – турсбор = BRV * (процент турсбора/100)
     * BRV – Базовая расчетная величина
     */
    private function calculateTourMarkup(): float
    {
        return $this->wageRateMin * $this->touristTaxPercent / 100;
    }
}
