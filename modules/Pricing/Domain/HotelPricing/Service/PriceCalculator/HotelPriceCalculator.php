<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\HotelPricing\Service\PriceCalculator;

use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Model\FormulaVariables;
use Module\Pricing\Domain\Markup\Service\MarkupCalculator;

class HotelPriceCalculator
{
    public function __construct() {}

    public function calculateNetPrice(FormulaVariables $variables)
    {
        $roomDayPriceCalculator = new RoomDayPriceCalculator();
        $roomDayPrice = $roomDayPriceCalculator
            ->setNetDayPrice($request->netDayPrice)
            ->setGrossDayPrice($request->grossDayPrice)
            ->calculate($request->date);

        $priceWithMarkup = $this->markupCalculator->applyMarkups($variables->clientId, $variables->roomId);
    }

    public function calculateGrossPrice(FormulaVariables $variables): float {}
}
