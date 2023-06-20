<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula;

abstract class AbstractRoomPriceFormula
{
    public function __construct(
        protected readonly MarkupVariables $markupVariables,
        protected readonly RoomVariables $variables
    ) {
    }

    abstract public function calculate(int|float $netValue): float;

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
