<?php

namespace Sdk\Booking\ValueObject\HotelBooking;

use Sdk\Shared\Contracts\Support\SerializableInterface;

final class RoomPrices implements SerializableInterface
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

    public function serialize(): array
    {
        return [
            'supplierPrice' => $this->supplierPrice->serialize(),
            'clientPrice' => $this->clientPrice->serialize(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new RoomPrices(
            supplierPrice: RoomPriceItem::deserialize($payload['supplierPrice']),
            clientPrice: RoomPriceItem::deserialize($payload['clientPrice']),
        );
    }
}
