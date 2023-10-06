<?php

namespace Module\Pricing\Domain\HotelPricing\Service\PriceCalculator;

use Carbon\CarbonInterface;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomDayPrice;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Formula\DayPriceFormula;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Formula\EstimatedPriceCalculator;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Model\CalculationResult;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Model\FormulaVariables;
use Module\Pricing\Domain\Markup\Service\MarkupCalculator;
use Module\Shared\Domain\ValueObject\Date;

class RoomDayPriceCalculator
{
    private readonly DayPriceFormula $dayPriceFormula;

    private readonly EstimatedPriceCalculator $estimatedPriceCalculator;

    private ?float $grossEstimatedPrice = null;

    private ?float $netEstimatedPrice = null;

    public function __construct(
        private readonly FormulaVariables $variables,
        private readonly MarkupCalculator $markupCalculator
    ) {
        $this->dayPriceFormula = new DayPriceFormula();
        $this->estimatedPriceCalculator = new EstimatedPriceCalculator();
    }

    public function calculate(CarbonInterface $date, float $netValue): RoomDayPrice
    {
        $grossResult = $this->calculateDayPrice(
            $this->grossEstimatedPrice,
            $date,
            $netValue,
            $this->variables->clientMarkupPercent
        );
        $netResult = $this->calculateDayPrice($this->netEstimatedPrice, $date, $netValue, 0);

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

    private function calculateDayPrice(
        CarbonInterface $date,
        float $value,
        int $markupPercent
    ): CalculationResult {
        return $this->dayPriceFormula->evaluate(
            $estimatedPrice ?? $this->calculateEstimatedValue($value, $markupPercent),
            $this->markupCalculator->calculate($value, $this->variables->clientId, $this->variables->roomId)
        );
    }

    private function calculateEstimatedValue(float $value, int $markupPercent): float
    {
        return $this->estimatedPriceCalculator->calculate(
            basePrice: $value,
            markupPercent: $markupPercent,
            ndsPercent: $this->variables->vatPercent,
            touristTax: $this->variables->touristTax,
            guestsCount: $this->variables->guestsCount
        );
    }
}
