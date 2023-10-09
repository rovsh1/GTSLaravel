<?php

namespace Module\Booking\Domain\Booking\ValueObject;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Module\Shared\Enum\CurrencyEnum;

final class BookingPrices implements SerializableDataInterface, CanEquate
{
    public function __construct(
        private readonly BookingPriceItem $supplierPrice,
        private readonly BookingPriceItem $clientPrice,
    ) {
    }

    public static function createEmpty(CurrencyEnum $netCurrency, CurrencyEnum $grossCurrency): BookingPrices
    {
        return new BookingPrices(
            BookingPriceItem::createEmpty($netCurrency),
            BookingPriceItem::createEmpty($grossCurrency),
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

    public function toData(): array
    {
        return [
            'supplierPrice' => $this->supplierPrice->toData(),
            'clientPrice' => $this->clientPrice->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new BookingPrices(
            supplierPrice: BookingPriceItem::fromData($data['supplierPrice']),
            clientPrice: BookingPriceItem::fromData($data['clientPrice']),
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
