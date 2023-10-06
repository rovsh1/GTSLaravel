<?php

namespace Module\Booking\Domain\HotelBooking\Service\PriceCalculator;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Module\Booking\Domain\HotelBooking\Adapter\RoomPriceCalculatorAdapterInterface;
use Module\Booking\Domain\HotelBooking\Entity\RoomBooking;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomDayPrice;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomDayPriceCollection;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPrice;
use Module\Pricing\Domain\Hotel\Support\FormulaUtil;
use Module\Shared\Domain\ValueObject\Date;

class RoomPriceEditor
{
    public function __construct(
        private readonly RoomDataHelperFactory $dataFactory,
        private readonly RoomPriceCalculatorAdapterInterface $roomPriceCalculatorAdapter,
    ) {
    }

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
            $items[] = $this->buildRoomDayPrice(
                $grossDayPrice ?? $this->calculateGross($dataHelper, $date),
                $netDayPrice ?? $this->calculateNet($dataHelper, $date),
                $date
            );
        }

        return new RoomPrice(
            grossDayValue: $grossDayPrice,
            netDayValue: $netDayPrice,
            dayPrices: new RoomDayPriceCollection($items)
        );
    }

    private function calculateGross(RoomDataHelper $dataHelper, DateTimeInterface $date): RoomDayPriceCollection
    {
        return $this->roomPriceCalculatorAdapter->calculateGross(
            clientId: $dataHelper->clientId(),
            roomId: $dataHelper->roomId(),
            rateId: $dataHelper->rateId(),
            isResident: $dataHelper->isResident(),
            guestsCount: $dataHelper->guestsCount(),
            outCurrency: $dataHelper->orderCurrency(),
            date: $date,
        );
    }

    private function calculateNet(RoomDataHelper $dataHelper, DateTimeInterface $date): RoomDayPriceCollection
    {
        return $this->roomPriceCalculatorAdapter->calculateNet(
            clientId: $dataHelper->clientId(),
            roomId: $dataHelper->roomId(),
            rateId: $dataHelper->rateId(),
            isResident: $dataHelper->isResident(),
            guestsCount: $dataHelper->guestsCount(),
            outCurrency: $dataHelper->orderCurrency(),
            date: $date,
        );
    }

    private function buildRoomDayPrice(float $grossPrice, float $netPrice, DateTimeInterface $date): RoomDayPrice
    {
        //@todo добавить наценки на ранний/поздний заезд
        return new RoomDayPrice(
            date: new Date($date),
            baseValue: 0,
            grossValue: $grossPrice,
            netValue: $netPrice,
            grossFormula: '',
            netFormula: ''
        );
    }

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
