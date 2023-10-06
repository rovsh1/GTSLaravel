<?php

namespace Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Model;

use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Enum\CurrencyEnum;

class FormulaVariables
{
    public function __construct(
        public readonly bool $isResident,
        public readonly int $guestsCount,
        public readonly Percent $vatPercent,
        public readonly float $touristTax,
        public readonly CurrencyEnum $hotelCurrency,
        public readonly CurrencyEnum $outCurrency,
    ) {}
}
