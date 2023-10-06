<?php

namespace Module\Booking\Domain\HotelBooking\Service\PriceCalculator;

use Carbon\CarbonInterface;
use Module\Booking\Domain\HotelBooking\Adapter\RoomPriceCalculatorAdapterInterface;
use Module\Booking\Domain\HotelBooking\Entity\RoomBooking;
use Module\Booking\Domain\HotelBooking\Service\PriceCalculator\Factory\RoomDataHelperFactory;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomDayPriceCollection;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPrice;
use Module\Pricing\Domain\HotelPricing\Service\PriceCalculator\Support\FormulaUtil;

class RoomPriceEditor
{
    public function __construct(
        private readonly RoomDataHelperFactory $dataFactory,
        private readonly RoomPriceCalculatorAdapterInterface $roomPriceCalculatorAdapter,
    ) {}

    public function recalculatePrices(RoomBooking $roomBooking): RoomPrice
    {
        return $this->calculate(
            $roomBooking,
            $roomBooking->price()->grossDayValue(),
            $roomBooking->price()->netDayValue()
        );
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

        $items = [];
        foreach ($dataHelper->bookingPeriodDates() as $date) {
            $datePrice = $this->roomPriceCalculatorAdapter->calculate(
                clientId: $dataHelper->clientId(),
                roomId: $dataHelper->roomId(),
                rateId: $dataHelper->rateId(),
                isResident: $dataHelper->isResident(),
                guestsCount: $dataHelper->guestsCount(),
                outCurrency: $dataHelper->orderCurrency(),
                date: $date,
                grossDayPrice: $grossDayPrice,
                netDayPrice: $netDayPrice,
            );
            //@todo добавить наценки на ранний/поздний заезд
            //@todo преобразовать в доменную модель
            $this->applyConditions();
        }

        return new RoomPrice(
            grossDayValue: $grossDayPrice,
            netDayValue: $netDayPrice,
            dayPrices: new RoomDayPriceCollection($items)
        );
    }

    private function applyConditions() {}

    private function calculateMarkupValue(CarbonInterface $date, float $value): float
    {
        $markupValue = 0.0;

        if ($this->variables->earlyCheckInPercent
            && $this->variables->startDate->format('Y-m-d') === $date->format('Y-m-d')) {
            $markupValue += FormulaUtil::calculatePercent($value, $this->variables->earlyCheckInPercent);
        }

        if ($this->variables->lateCheckOutPercent
            && $this->variables->endDate->format('Y-m-d') === $date->format('Y-m-d')) {
            $markupValue += FormulaUtil::calculatePercent($value, $this->variables->lateCheckOutPercent);
        }

        return $markupValue;
    }
}
