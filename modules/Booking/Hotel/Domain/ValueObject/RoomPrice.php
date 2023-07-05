<?php

namespace Module\Booking\Hotel\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class RoomPrice implements SerializableDataInterface
{
    public function __construct(
        private readonly float $netValue,
        private readonly ManualChangablePrice $hoPrice,
        private readonly ManualChangablePrice $boPrice,
        private readonly RoomPriceDetails $hoPriceDetails,
        private readonly RoomPriceDetails $boPriceDetails,
    ) {}

    public static function buildEmpty(): static
    {
        return new static(
            0,
            new ManualChangablePrice(0),
            new ManualChangablePrice(0),
            new RoomPriceDetails(new DayPriceCollection(), null),
            new RoomPriceDetails(new DayPriceCollection(), null)
        );
    }

    public function netValue(): float
    {
        return $this->netValue;
    }

    public function hoValue(): ManualChangablePrice
    {
        return $this->hoPrice;
    }

    public function boValue(): ManualChangablePrice
    {
        return $this->boPrice;
    }

    public function hoPriceDetails(): RoomPriceDetails
    {
        return $this->hoPriceDetails;
    }

    public function boPriceDetails(): RoomPriceDetails
    {
        return $this->boPriceDetails;
    }

    public function toData(): array
    {
        return [
            'netValue' => $this->netValue,
            'hoPrice' => $this->hoPrice->toData(),
            'boPrice' => $this->boPrice->toData(),
            'boPriceDetails' => $this->boPriceDetails->toData(),
            'hoPriceDetails' => $this->hoPriceDetails->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new RoomPrice(
            netValue: $data['netValue'],
            hoPrice: ManualChangablePrice::fromData($data['hoPrice']),
            boPrice: ManualChangablePrice::fromData($data['boPrice']),
            hoPriceDetails: RoomPriceDetails::fromData($data['hoPriceDetails']),
            boPriceDetails: RoomPriceDetails::fromData($data['boPriceDetails']),
        );
    }
}
