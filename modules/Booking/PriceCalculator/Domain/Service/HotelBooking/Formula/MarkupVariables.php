<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula;

class MarkupVariables
{
    public function __construct(
        public readonly int $vatPercent,
        public readonly int $clientMarkupPercent,
        public readonly ?int $earlyCheckInPercent,
        public readonly ?int $lateCheckOutPercent,
        public readonly float $touristTax
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
