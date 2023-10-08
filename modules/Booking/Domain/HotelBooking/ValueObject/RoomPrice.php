<?php

namespace Module\Booking\Domain\HotelBooking\ValueObject;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class RoomPrice implements SerializableDataInterface
{
    public static function buildEmpty(): RoomPrice
    {
        return new RoomPrice(
            RoomPriceItem::createEmpty(),
            RoomPriceItem::createEmpty(),
        );
    }

    public function __construct(
        private readonly RoomPriceItem $supplierPrice,
        private readonly RoomPriceItem $clientPrice,
    ) {
    }

    public function supplierPrice(): RoomPriceItem
    {
        return $this->supplierPrice;
    }

    public function clientPrice(): RoomPriceItem
    {
        return $this->clientPrice;
    }

    public function toData(): array
    {
        return [
            'supplierPrice' => $this->supplierPrice->toData(),
            'clientPrice' => $this->clientPrice->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new RoomPrice(
            supplierPrice: RoomPriceItem::fromData($data['supplierPrice']),
            clientPrice: RoomPriceItem::fromData($data['clientPrice']),
        );
    }
}
