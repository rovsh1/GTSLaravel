<?php

namespace Module\Booking\PriceCalculator\Domain\Service\Hotel;

use Module\Booking\Hotel\Domain\ValueObject\RoomDayPrice;
use Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula\BONonResidentDayPriceFormula;
use Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula\BOResidentDayPriceFormula;
use Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula\BORoomPriceFormula;
use Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula\DayPriceFormulaInterface;
use Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula\HONonResidentDayPriceFormula;
use Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula\HOResidentDayPriceFormula;
use Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula\HORoomPriceFormula;
use Module\Booking\PriceCalculator\Domain\Service\Hotel\Support\FormulaVariables;
use Module\Booking\PriceCalculator\Domain\ValueObject\CalculationResult;

class RoomDayPriceCalculator
{
    private BORoomPriceFormula $boFormula;

    private HORoomPriceFormula $hoFormula;

    private ?float $boDayPrice = null;

    private ?float $hoDayPrice = null;

    private DayPriceFormulaInterface $boDayPriceCalculator;

    private DayPriceFormulaInterface $hoDayPriceCalculator;

    public function __construct(
        private readonly FormulaVariables $variables
    ) {
        $this->boFormula = new BORoomPriceFormula($variables->earlyCheckInPercent, $variables->lateCheckOutPercent);
        $this->hoFormula = new HORoomPriceFormula($variables->earlyCheckInPercent, $variables->lateCheckOutPercent);
        $this->boDayPriceCalculator = $this->makeBODayPriceCalculator();
        $this->hoDayPriceCalculator = $this->makeHODayPriceCalculator();
    }

    public function calculate($date, float $netValue): RoomDayPrice
    {
        $boResult = $this->getBoResult($netValue);
        $hoResult = $this->getHoResult($netValue);

        return new RoomDayPrice(
            date: $date,
            netValue: $netValue,
            boValue: $boResult->value,
            hoValue: $hoResult->value,
            boFormula: $boResult->notes,
            hoFormula: $hoResult->notes,
        );
    }

    public function setBODayPrice(float $price): static
    {
        $this->boDayPrice = $price;

        return $this;
    }

    public function setHODayPrice(float $price): static
    {
        $this->hoDayPrice = $price;

        return $this;
    }

    private function makeBODayPriceCalculator(): DayPriceFormulaInterface
    {
        return $this->variables->isResident
            ? new BOResidentDayPriceFormula(
                clientMarkupPercent: $this->variables->clientMarkupPercent,
                ndsPercent: $this->variables->vatPercent
            )
            : new BONonResidentDayPriceFormula(
                clientMarkupPercent: $this->variables->clientMarkupPercent,
                ndsPercent: $this->variables->vatPercent,
                touristTax: $this->variables->touristTax,
                guestsCount: $this->variables->guestsCount
            );
    }

    private function makeHODayPriceCalculator(): DayPriceFormulaInterface
    {
        return $this->variables->isResident
            ? new HOResidentDayPriceFormula(
                ndsPercent: $this->variables->vatPercent
            )
            : new HONonResidentDayPriceFormula(
                ndsPercent: $this->variables->vatPercent,
                touristTax: $this->variables->touristTax,
                guestsCount: $this->variables->guestsCount
            );
    }

    private function getBoResult(float $value): CalculationResult
    {
        return $this->boFormula->evaluate(
            $this->boDayPrice ?? $this->boDayPriceCalculator->calculate($value)
        );
    }

    private function getHoResult(float $value): CalculationResult
    {
        return $this->hoFormula->evaluate(
            $this->hoDayPrice ?? $this->hoDayPriceCalculator->calculate($value)
        );
    }
}