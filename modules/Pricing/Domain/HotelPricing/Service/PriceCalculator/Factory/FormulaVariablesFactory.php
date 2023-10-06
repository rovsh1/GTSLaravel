<?php

namespace Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Factory;

use Module\Pricing\Application\Request\CalculateHotelRoomPriceRequestDto;
use Module\Pricing\Domain\HotelPricing\Service\HotelRoomValuesStorage;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Model\FormulaVariables;
use Module\Pricing\Domain\Markup\Service\MarkupCalculator;
use Module\Shared\Domain\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Domain\Service\ApplicationConstantsInterface;
use Module\Shared\Enum\CurrencyEnum;

class FormulaVariablesFactory
{
    public function __construct(
        private readonly HotelRoomValuesStorage $hotelRoomValuesStorage,
        private readonly ApplicationConstantsInterface $applicationConstants,
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter,
        private readonly MarkupCalculator $markupCalculator,
    ) {}

    public function fromRequest(CalculateHotelRoomPriceRequestDto $request): FormulaVariables
    {
        $roomVatPercent = $this->hotelRoomValuesStorage->getVatPercent($request->roomId);
        $roomTouristTaxPercent = $this->hotelRoomValuesStorage->getTouristTaxPercent($request->roomId);
        $hotelCurrency = $this->hotelRoomValuesStorage->getHotelCurrency($request->roomId);

        return new FormulaVariables(
            isResident: $request->isResident,
            guestsCount: $request->guestsCount,
            vatPercent: $roomVatPercent,
            touristTax: $this->calculateTouristTax(
                $roomTouristTaxPercent->value(),
                $hotelCurrency,
                $request->outCurrency
            ),
            hotelCurrency: $hotelCurrency,
            outCurrency: $request->outCurrency
        );
    }

    private function calculateTouristTax(int $taxPercent, CurrencyEnum $hotelCurrency, CurrencyEnum $outCurrency): float
    {
        $value = $this->applicationConstants->basicCalculatedValue() * $taxPercent / 100;

        return $this->currencyRateAdapter->convertNetRate(
            $value,
            $hotelCurrency,
            $outCurrency,
            'UZ'
        );
    }
}
