<?php

namespace Module\Pricing\Domain\HotelBooking\Service;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Module\Pricing\Domain\Hotel\Support\FormulaUtil;
use Module\Pricing\Domain\HotelBooking\Adapter\RoomPriceCalculatorAdapterInterface;
use Module\Pricing\Domain\HotelBooking\Entity\RoomBooking;
use Module\Pricing\Domain\HotelBooking\ValueObject\RoomPrice;
use Module\Pricing\Domain\HotelBooking\ValueObject\RoomPriceDayPart;
use Module\Pricing\Domain\HotelBooking\ValueObject\RoomPriceDayPartCollection;
use Module\Pricing\Domain\HotelBooking\ValueObject\RoomPriceItem;
use Module\Shared\Domain\ValueObject\Date;

class RoomPriceBuilder
{
    private RoomDataHelper $dataHelper;

    private ?float $supplierManualValue;

    private ?float $clientManualValue;

    public function __construct(
        private readonly RoomDataHelperFactory $dataFactory,
        private readonly RoomPriceCalculatorAdapterInterface $roomPriceCalculatorAdapter,
    ) {
    }

    public function forBooking(RoomBooking $roomBooking): static
    {
        $this->dataHelper = $this->dataFactory->fromRoomBooking($roomBooking);

        return $this
            ->setSupplierManualValue($roomBooking->price()->supplierPrice()->manualDayValue())
            ->setClientManualValue($roomBooking->price()->clientPrice()->manualDayValue());
    }

    public function setSupplierManualValue(?float $value): static
    {
        $this->supplierManualValue = $value;

        return $this;
    }

    public function setClientManualValue(?float $value): static
    {
        $this->clientManualValue = $value;

        return $this;
    }

    public function build(): RoomPrice
    {
        $supplierDayParts = [];
        $clientDayParts = [];
        foreach ($this->dataHelper->bookingPeriodDates() as $date) {
            $supplierDayParts[] = $this->buildRoomPriceDayPart(
                $this->supplierManualValue ?? $this->calculateSupplierPrice($date),
                $date
            );
            $clientDayParts[] = $this->buildRoomPriceDayPart(
                $this->clientManualValue ?? $this->calculateClientPrice($date),
                $date
            );
        }

        return new RoomPrice(
            supplierPrice: new RoomPriceItem(
                dayParts: new RoomPriceDayPartCollection($supplierDayParts),
                manualDayValue: $this->supplierManualValue
            ),
            clientPrice: new RoomPriceItem(
                dayParts: new RoomPriceDayPartCollection($clientDayParts),
                manualDayValue: $this->clientManualValue
            )
        );
    }

    private function calculateSupplierPrice(DateTimeInterface $date): float
    {
        return $this->roomPriceCalculatorAdapter->calculateSupplierPrice(
            clientId: $this->dataHelper->clientId(),
            roomId: $this->dataHelper->roomId(),
            rateId: $this->dataHelper->rateId(),
            isResident: $this->dataHelper->isResident(),
            guestsCount: $this->dataHelper->guestsCount(),
            outCurrency: $this->dataHelper->orderCurrency(),
            date: $date,
        );
    }

    private function calculateClientPrice(DateTimeInterface $date): float
    {
        return $this->roomPriceCalculatorAdapter->calculateClientPrice(
            clientId: $this->dataHelper->clientId(),
            roomId: $this->dataHelper->roomId(),
            rateId: $this->dataHelper->rateId(),
            isResident: $this->dataHelper->isResident(),
            guestsCount: $this->dataHelper->guestsCount(),
            outCurrency: $this->dataHelper->orderCurrency(),
            date: $date,
        );
    }

    private function buildRoomPriceDayPart(float $grossPrice, DateTimeInterface $date): RoomPriceDayPart
    {
        //@todo добавить наценки на ранний/поздний заезд
        return new RoomPriceDayPart(
            date: new Date($date),
            value: $grossPrice,
            formula: ''
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
