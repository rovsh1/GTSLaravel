<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula;

use Module\Booking\PriceCalculator\Domain\ValueObject\CalculationResult;

abstract class AbstractRoomPriceFormula
{
    public function __construct(
        protected readonly MarkupVariables $markupVariables,
        protected readonly RoomVariables $variables
    ) {
    }

    abstract public function calculate(int|float $netValue): CalculationResult;

    protected function calculateConditionMarkup(float $value): float
    {
        return $value * $this->markupVariables->earlyCheckInPercent / 100
            + $value * $this->markupVariables->lateCheckOutPercent / 100;
    }

    /**
     * НДС общий для резидентов и нерезидентов
     */
    protected function calculateNds(int|float $value): float
    {
        return $value * $this->markupVariables->vatPercent / 100;
    }
}
