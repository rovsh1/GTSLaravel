<?php

namespace Module\Pricing\Domain\Hotel\Service;

use Module\Pricing\Domain\Hotel\Dto\CalculationDto;
use Module\Pricing\Domain\Hotel\Support\CalculationBuilder;
use Module\Shared\ValueObject\MarkupValue;

class RoomDayPriceCalculatorFormula
{
    private readonly CalculationBuilder $calculationBuilder;

    private float $priceForMarkup;

    public function __construct()
    {
        $this->calculationBuilder = new CalculationBuilder();
    }

    public function calculateBase(float $baseValue, int $vat, int $touristTax, int $guestCount): void
    {
        $this->calculationBuilder
            ->base($baseValue, 'Базовое')
            ->plus(self::percent($baseValue, $vat), "$vat% НДС")
            ->plus(self::percent($baseValue, $touristTax), "$touristTax% Турсбор")
            ->multiply($guestCount, 'Гостей');

        $this->priceForMarkup = $this->calculationBuilder->resultValue();
    }

    public function setBaseManually(float $value): void
    {
        $this->calculationBuilder->base($value, 'Базовое');
        $this->priceForMarkup = $value;
    }

    public function applyClientMarkup(MarkupValue $markup): void
    {
        $markupValue = $markup->calculate($this->priceForMarkup);
        $this->calculationBuilder->plus($markupValue, "$markup Наценка");
        $this->priceForMarkup += $markupValue;
    }

    public function applyEarlyCheckinMarkup(MarkupValue $markup): void
    {
        $this->calculationBuilder->plus($markup->calculate($this->priceForMarkup), "$markup Ранний заезд");
    }

    public function applyLateCheckoutMarkup(MarkupValue $markup): void
    {
        $this->calculationBuilder->plus($markup->calculate($this->priceForMarkup), "$markup Поздний выезд");
    }

    public function result(): CalculationDto
    {
        return $this->calculationBuilder->build();
    }

    private static function percent(int|float $value, int $percent): float|int
    {
        return $value * $percent / 100;
    }
}