<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator;

use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support\FormulaVariablesFactory;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support\NetPriceFetcher;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support\RoomDataHelperFactory;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomDayPriceCollection;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomPrice;

class RoomPriceEditor
{
    public function __construct(
        private readonly RoomDataHelperFactory $dataFactory,
        private readonly FormulaVariablesFactory $variablesFactory,
        private readonly NetPriceFetcher $netPriceFetcher
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

    public function setManuallyBoPrice(RoomBooking $roomBooking, float $manuallyDayPrice): RoomPrice
    {
        return $this->calculate($roomBooking, $manuallyDayPrice, $roomBooking->price()->netDayValue());
    }

    public function setCalculatedBoPrice(RoomBooking $roomBooking): RoomPrice
    {
        return $this->calculate($roomBooking, null, $roomBooking->price()->netDayValue());
    }

    public function setManuallyHoPrice(RoomBooking $roomBooking, float $manuallyDayPrice): RoomPrice
    {
        return $this->calculate($roomBooking, $roomBooking->price()->grossDayValue(), $manuallyDayPrice);
    }

    public function setCalculatedHoPrice(RoomBooking $roomBooking): RoomPrice
    {
        return $this->calculate($roomBooking, $roomBooking->price()->grossDayValue(), null);
    }

    private function calculate(
        RoomBooking $roomBooking,
        ?float $boDayPrice,
        ?float $hoDayPrice,
    ): RoomPrice {
        $dataHelper = $this->dataFactory->fromRoomBooking($roomBooking);
        $priceBuilder = new RoomDayPriceCalculator(
            $this->variablesFactory->fromDataHelper($dataHelper)
        );
        if ($boDayPrice) {
            $priceBuilder->setBODayPrice($boDayPrice);
        }
        if ($hoDayPrice) {
            $priceBuilder->setHODayPrice($hoDayPrice);
        }

        $roomId = $dataHelper->roomId();
        $rateId = $dataHelper->rateId();
        $isResident = $dataHelper->isResident();
        $guestsCount = $dataHelper->guestsCount();
        $orderCurrency = $dataHelper->orderCurrency();
        $hotelCurrency = $dataHelper->hotelCurrency();

        $items = [];
        foreach ($dataHelper->bookingPeriodDates() as $date) {
            $netPrice = $this->netPriceFetcher->fetch(
                roomId: $roomId,
                rateId: $rateId,
                isResident: $isResident,
                guestsCount: $guestsCount,
                orderCurrency: $orderCurrency,
                hotelCurrency: $hotelCurrency,
                date: $date
            );
            $items[] = $priceBuilder->calculate($date, $netPrice);
        }

        return new RoomPrice(
            grossDayValue: $boDayPrice,
            netDayValue: $hoDayPrice,
            dayPrices: new RoomDayPriceCollection($items)
        );
    }

//    public function calculateByVariables(DayPriceCollection $dayPrices, Variables $variables): RoomDayPriceCollection
//    {
//        $boFormula = new BORoomPriceFormula(
//            $variables->earlyCheckInPercent,
//            $variables->lateCheckOutPercent
//        );
//        $hoFormula = new HORoomPriceFormula(
//            $variables->earlyCheckInPercent,
//            $variables->lateCheckOutPercent
//        );
//        //$this->makeBOFormula($calculateVariables);
//        //$hoFormula = $this->makeHOFormula($calculateVariables);
//
//        $items = [];
//        /** @var DayPrice $dayPrice */
//        foreach ($dayPrices as $dayPrice) {
//            $hoResult = $hoFormula->evaluate($dayPrice->price);
//            $boResult = $boFormula->evaluate($dayPrice->price);
//            $items[] = new RoomDayPrice(
//                $dayPrice->date,
//                $dayPrice->price,
//                $boResult->value,
//                $hoResult->value,
//                $boResult->notes,
//                $hoResult->notes,
//            );
//        }
//
//        return new RoomDayPriceCollection($items);
//    }
}
