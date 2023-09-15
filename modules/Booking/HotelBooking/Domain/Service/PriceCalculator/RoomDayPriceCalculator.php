<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator;

use Carbon\CarbonInterface;
use Module\Booking\Common\Domain\Support\CalculationResult;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula\GrossNonResidentDayPriceFormula;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula\GrossResidentDayPriceFormula;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula\GrossRoomPriceFormula;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula\DayPriceFormulaInterface;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula\NetNonResidentDayPriceFormula;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula\NetResidentDayPriceFormula;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula\NetRoomPriceFormula;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support\FormulaVariables;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomDayPrice;
use Module\Shared\Domain\ValueObject\Date;

class RoomDayPriceCalculator
{
    private GrossRoomPriceFormula $grossFormula;

    private NetRoomPriceFormula $netFormula;

    private ?float $grossDayPrice = null;

    private ?float $netDayPrice = null;

    private DayPriceFormulaInterface $grossDayPriceCalculator;

    private DayPriceFormulaInterface $netDayPriceCalculator;

    public function __construct(
        private readonly FormulaVariables $variables
    ) {
        $this->grossFormula = new GrossRoomPriceFormula($variables->earlyCheckInPercent, $variables->lateCheckOutPercent);
        $this->netFormula = new NetRoomPriceFormula($variables->earlyCheckInPercent, $variables->lateCheckOutPercent);
        $this->grossDayPriceCalculator = $this->makeGrossDayPriceCalculator();
        $this->netDayPriceCalculator = $this->makeNetDayPriceCalculator();
    }

    public function calculate(CarbonInterface $date, float $netValue): RoomDayPrice
    {
        $grossResult = $this->getGrossResult($netValue);
        $netResult = $this->getNetResult($netValue);

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
        $this->grossDayPrice = $price;

        return $this;
    }

    public function setNetDayPrice(float $price): static
    {
        $this->netDayPrice = $price;

        return $this;
    }

    private function makeGrossDayPriceCalculator(): DayPriceFormulaInterface
    {
        return $this->variables->isResident
            ? new GrossResidentDayPriceFormula(
                clientMarkupPercent: $this->variables->clientMarkupPercent,
                ndsPercent: $this->variables->vatPercent
            )
            : new GrossNonResidentDayPriceFormula(
                clientMarkupPercent: $this->variables->clientMarkupPercent,
                ndsPercent: $this->variables->vatPercent,
                touristTax: $this->variables->touristTax,
                guestsCount: $this->variables->guestsCount
            );
    }

    private function makeNetDayPriceCalculator(): DayPriceFormulaInterface
    {
        return $this->variables->isResident
            ? new NetResidentDayPriceFormula(
                ndsPercent: $this->variables->vatPercent
            )
            : new NetNonResidentDayPriceFormula(
                ndsPercent: $this->variables->vatPercent,
                touristTax: $this->variables->touristTax,
                guestsCount: $this->variables->guestsCount
            );
    }

    private function getGrossResult(float $value): CalculationResult
    {
        return $this->grossFormula->evaluate(
            $this->grossDayPrice ?? $this->grossDayPriceCalculator->calculate($value)
        );
    }

    private function getNetResult(float $value): CalculationResult
    {
        return $this->netFormula->evaluate(
            $this->netDayPrice ?? $this->netDayPriceCalculator->calculate($value)
        );
    }
}
