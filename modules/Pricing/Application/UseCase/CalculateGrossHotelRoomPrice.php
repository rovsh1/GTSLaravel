<?php

declare(strict_types=1);

namespace Module\Pricing\Application\UseCase;

use Module\Pricing\Application\Request\CalculateHotelRoomPriceRequestDto;
use Module\Pricing\Domain\HotelPricing\Service\HotelRoomValuesStorage;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Factory\FormulaVariablesFactory;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\HotelPriceCalculator;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Model\CalculationResult;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\RoomDayPriceCalculator;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Support\BasePriceFetcher;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CalculateGrossHotelRoomPrice implements UseCaseInterface
{
    public function __construct(
        private readonly FormulaVariablesFactory $formulaVariablesFactory,
        private readonly HotelPriceCalculator $hotelPriceCalculator,
        private readonly BasePriceFetcher $priceFetcher,
        private readonly HotelRoomValuesStorage $hotelRoomValuesStorage
    ) {}

    public function execute(CalculateHotelRoomPriceRequestDto $request)
    {
        $roomDayBasePrice = $request->dayPrice;
        if ($roomDayBasePrice === null) {
            $roomDayBasePrice = $this->fetchRoomDayPrice($request);
        }


        $roomDayPriceCalculator = new RoomDayPriceCalculator();
        $this->hotelPriceCalculator->calculateGrossPrice();

        return new CalculationResult(
            0,
            '',
        );
    }

    private function fetchRoomDayPrice(CalculateHotelRoomPriceRequestDto $request): float
    {
        $hotelCurrency = $this->hotelRoomValuesStorage->getHotelCurrency($request->roomId);

        return $this->priceFetcher->fetch(
            $request->roomId,
            $request->rateId,
            $request->isResident,
            $request->guestsCount,
            $request->outCurrency,
            $hotelCurrency,
            $request->date
        );
    }
}
