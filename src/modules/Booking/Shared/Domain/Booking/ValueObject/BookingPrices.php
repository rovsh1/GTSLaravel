<?php

namespace Module\Booking\Shared\Domain\Booking\ValueObject;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Contracts\Support\SerializableInterface;
use Module\Shared\Enum\CurrencyEnum;

final class BookingPrices implements SerializableInterface, CanEquate
{
    public function __construct(
        private readonly BookingPriceItem $supplierPrice,
        private readonly BookingPriceItem $clientPrice,
    ) {
    }

    public static function createEmpty(CurrencyEnum $supplierCurrency, CurrencyEnum $clientCurrency): BookingPrices
    {
        return new BookingPrices(
            BookingPriceItem::createEmpty($supplierCurrency),
            BookingPriceItem::createEmpty($clientCurrency),
        );
    }

    public function supplierPrice(): BookingPriceItem
    {
        return $this->supplierPrice;
    }

    public function clientPrice(): BookingPriceItem
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
        return new BookingPrices(
            supplierPrice: BookingPriceItem::deserialize($payload['supplierPrice']),
            clientPrice: BookingPriceItem::deserialize($payload['clientPrice']),
        );
    }

    /**
     * @param static $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        return $this->supplierPrice->isEqual($b->supplierPrice)
            && $this->clientPrice->isEqual($b->clientPrice);
    }
}
