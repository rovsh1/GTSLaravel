<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula;

use Carbon\CarbonInterface;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Model\CalculationResult;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Model\FormulaVariables;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support\FormulaUtil;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomDayPrice;
use Module\Shared\Domain\ValueObject\Date;

class RoomDayPriceCalculator
{
    private readonly DayPriceFormula $dayPriceFormula;

    private readonly EstimatedPriceCalculator $estimatedPriceCalculator;

    private ?float $grossEstimatedPrice = null;

    private ?float $netEstimatedPrice = null;

    public function __construct(
        private readonly FormulaVariables $variables,
    ) {
        $this->dayPriceFormula = new DayPriceFormula();
        $this->estimatedPriceCalculator = new EstimatedPriceCalculator();
    }

    public function calculate(CarbonInterface $date, float $netValue): RoomDayPrice
    {
        $grossResult = $this->calculateDayPrice($this->grossEstimatedPrice, $date, $netValue);
        $netResult = $this->calculateDayPrice($this->netEstimatedPrice, $date, $netValue);

        return new RoomDayPrice(
            date: new Date($date->toIso8601String()),
            baseValue: $netValue,
            grossValue: $grossResult->value,
            netValue: $netResult->value,
            grossFormula: $grossResult->notes,
            netFormula: $netResult->notes,
        );
    }

    public function setGrossDayPrice(float $price): static
    {
        $this->grossEstimatedPrice = $price;

        return $this;
    }

    public function setNetDayPrice(float $price): static
    {
        $this->netEstimatedPrice = $price;

        return $this;
    }

    private function calculateDayPrice(?float $estimatedPrice, CarbonInterface $date, float $value): CalculationResult
    {
        return $this->dayPriceFormula->evaluate(
            $estimatedPrice ?? $this->calculateEstimatedValue($value),
            $this->calculateMarkupValue($date, $value)
        );
    }

    private function calculateEstimatedValue(float $value): float
    {
        return $this->estimatedPriceCalculator->calculate(
            basePrice: $value,
            markupPercent: $this->variables->clientMarkupPercent,
            ndsPercent: $this->variables->vatPercent,
            touristTax: $this->variables->touristTax,
            guestsCount: $this->variables->guestsCount
        );
    }

    private function calculateMarkupValue(CarbonInterface $date, float $value): float
    {
        $markupValue = 0.0;

        if ($this->variables->earlyCheckInPercent
            && $this->variables->startDate->format('Y-m-d') === $date->format('Y-m-d')) {
            $markupValue += FormulaUtil::calculatePercent($value, $this->variables->earlyCheckInPercent);
        }

        if ($this->variables->lateCheckOutPercent
            && $this->variables->endDate->format('Y-m-d') === $date->format('Y-m-d')) {
            $markupValue += FormulaUtil::calculatePercent($value, $this->variables->lateCheckOutPercent);
        }

        return $markupValue;
    }
}
