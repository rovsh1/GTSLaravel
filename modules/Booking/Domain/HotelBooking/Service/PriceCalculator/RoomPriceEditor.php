<?php

namespace Module\Booking\Domain\HotelBooking\Service\PriceCalculator;

use Module\Booking\Domain\HotelBooking\Entity\RoomBooking;
use Module\Booking\Domain\HotelBooking\Service\PriceCalculator\Factory\FormulaVariablesFactory;
use Module\Booking\Domain\HotelBooking\Service\PriceCalculator\Factory\RoomDataHelperFactory;
use Module\Booking\Domain\HotelBooking\Service\PriceCalculator\Formula\RoomDayPriceCalculator;
use Module\Booking\Domain\HotelBooking\Service\PriceCalculator\Support\BasePriceFetcher;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomDayPriceCollection;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPrice;

class RoomPriceEditor
{
    public function __construct(
        private readonly RoomDataHelperFactory $dataFactory,
        private readonly FormulaVariablesFactory $variablesFactory,
        private readonly BasePriceFetcher $basePriceFetcher
    ) {}

    public function recalculatePrices(RoomBooking $roomBooking): RoomPrice
    {
        return $this->calculate($roomBooking, $roomBooking->price()->grossDayValue(), $roomBooking->price()->netDayValue());
    }

    public function setCalculatedPrices(RoomBooking $roomBooking): RoomPrice
    {
        return $this->calculate($roomBooking, null, null);
    }

    public function setManuallyPrices(RoomBooking $roomBooking, int|float $boDayPrice, int|float $hoDayPrice): RoomPrice
    {
        return $this->calculate($roomBooking, $boDayPrice, $hoDayPrice);
    }

    public function setManuallyGrossPrice(RoomBooking $roomBooking, float $manuallyDayPrice): RoomPrice
    {
        return $this->calculate($roomBooking, $manuallyDayPrice, $roomBooking->price()->netDayValue());
    }

    public function setCalculatedGrossPrice(RoomBooking $roomBooking): RoomPrice
    {
        return $this->calculate($roomBooking, null, $roomBooking->price()->netDayValue());
    }

    public function setManuallyNetPrice(RoomBooking $roomBooking, float $manuallyDayPrice): RoomPrice
    {
        return $this->calculate($roomBooking, $roomBooking->price()->grossDayValue(), $manuallyDayPrice);
    }

    public function setCalculatedNetPrice(RoomBooking $roomBooking): RoomPrice
    {
        return $this->calculate($roomBooking, $roomBooking->price()->grossDayValue(), null);
    }

    private function calculate(
        RoomBooking $roomBooking,
        ?float $grossDayPrice,
        ?float $netDayPrice,
    ): RoomPrice {
        $dataHelper = $this->dataFactory->fromRoomBooking($roomBooking);
        $priceBuilder = new RoomDayPriceCalculator(
            $this->variablesFactory->fromDataHelper($dataHelper)
        );
        if ($grossDayPrice) {
            $priceBuilder->setGrossDayPrice($grossDayPrice);
        }
        if ($netDayPrice) {
            $priceBuilder->setNetDayPrice($netDayPrice);
        }

        $roomId = $dataHelper->roomId();
        $rateId = $dataHelper->rateId();
        $isResident = $dataHelper->isResident();
        $guestsCount = $dataHelper->guestsCount();
        $orderCurrency = $dataHelper->orderCurrency();
        $hotelCurrency = $dataHelper->hotelCurrency();

        $items = [];
        foreach ($dataHelper->bookingPeriodDates() as $date) {
            $basePrice = $this->basePriceFetcher->fetch(
                roomId: $roomId,
                rateId: $rateId,
                isResident: $isResident,
                guestsCount: $guestsCount,
                orderCurrency: $orderCurrency,
                hotelCurrency: $hotelCurrency,
                date: $date
            );
            $items[] = $priceBuilder->calculate($date, $basePrice);
        }

        return new RoomPrice(
            grossDayValue: $grossDayPrice,
            netDayValue: $netDayPrice,
            dayPrices: new RoomDayPriceCollection($items)
        );
    }
}
