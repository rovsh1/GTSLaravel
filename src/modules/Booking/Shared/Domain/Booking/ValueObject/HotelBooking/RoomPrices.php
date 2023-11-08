<?php

namespace Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking;

use Module\Shared\Contracts\Support\SerializableDataInterface;

final class RoomPrices implements SerializableDataInterface
{
    public static function buildEmpty(): RoomPrices
    {
        return new RoomPrices(
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
        return new RoomPrices(
            supplierPrice: RoomPriceItem::fromData($data['supplierPrice']),
            clientPrice: RoomPriceItem::fromData($data['clientPrice']),
        );
    }
}
