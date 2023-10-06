<?php

namespace Module\Pricing\Domain\HotelBooking\Service;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Module\Booking\Domain\HotelBooking\Adapter\RoomPriceCalculatorAdapterInterface;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomDayPrice;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomDayPriceCollection;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPrice;
use Module\Pricing\Domain\Hotel\Support\FormulaUtil;
use Module\Pricing\Domain\HotelBooking\Entity\RoomBooking;
use Module\Shared\Domain\ValueObject\Date;

class RoomPriceBuilder
{
    private RoomDataHelper $dataHelper;

    private ?float $manualGrossValue;

    private ?float $manualNetValue;

    public function __construct(
        private readonly RoomDataHelperFactory $dataFactory,
        private readonly RoomPriceCalculatorAdapterInterface $roomPriceCalculatorAdapter,
    ) {
    }

    public function forBooking(RoomBooking $roomBooking): static
    {
        $this->dataHelper = $this->dataFactory->fromRoomBooking($roomBooking);

        return $this
            ->withManualGrossValue($roomBooking->price()->grossDayValue())
            ->withManualNetValue($roomBooking->price()->netDayValue());
    }

    public function withManualGrossValue(?float $value): static
    {
        $this->manualGrossValue = $value;

        return $this;
    }

    public function withManualNetValue(?float $value): static
    {
        $this->manualNetValue = $value;

        return $this;
    }

    public function build(): RoomPrice
    {
        $items = [];
        foreach ($this->dataHelper->bookingPeriodDates() as $date) {
            $items[] = $this->buildRoomDayPrice(
                $this->manualGrossValue ?? $this->calculateGross($date),
                $this->manualNetValue ?? $this->calculateNet($date),
                $date
            );
        }

        return new RoomPrice(
            grossDayValue: $this->manualGrossValue,
            netDayValue: $this->manualNetValue,
            dayPrices: new RoomDayPriceCollection($items)
        );
    }

    private function calculateGross(DateTimeInterface $date): RoomDayPriceCollection
    {
        return $this->roomPriceCalculatorAdapter->calculateGross(
            clientId: $this->dataHelper->clientId(),
            roomId: $this->dataHelper->roomId(),
            rateId: $this->dataHelper->rateId(),
            isResident: $this->dataHelper->isResident(),
            guestsCount: $this->dataHelper->guestsCount(),
            outCurrency: $this->dataHelper->orderCurrency(),
            date: $date,
        );
    }

    private function calculateNet(DateTimeInterface $date): RoomDayPriceCollection
    {
        return $this->roomPriceCalculatorAdapter->calculateNet(
            clientId: $this->dataHelper->clientId(),
            roomId: $this->dataHelper->roomId(),
            rateId: $this->dataHelper->rateId(),
            isResident: $this->dataHelper->isResident(),
            guestsCount: $this->dataHelper->guestsCount(),
            outCurrency: $this->dataHelper->orderCurrency(),
            date: $date,
        );
    }

    private function buildRoomDayPrice(float $grossPrice, float $netPrice, DateTimeInterface $date): RoomDayPrice
    {
        //@todo добавить наценки на ранний/поздний заезд
        return new RoomDayPrice(
            date: new Date($date),
            grossValue: $grossPrice,
            netValue: $netPrice,
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
