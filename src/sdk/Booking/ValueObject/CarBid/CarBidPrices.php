<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject\CarBid;

use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\Enum\CurrencyEnum;

class CarBidPrices implements SerializableInterface
{
    public function __construct(
        private readonly CarBidPriceItem $supplierPrice,
        private readonly CarBidPriceItem $clientPrice,
    ) {}

    public static function createEmpty(CurrencyEnum $netCurrency, CurrencyEnum $grossCurrency): static
    {
        return new static(
            CarBidPriceItem::createEmpty($netCurrency),
            CarBidPriceItem::createEmpty($grossCurrency),
        );
    }

    public function supplierPrice(): CarBidPriceItem
    {
        return $this->supplierPrice;
    }

    public function clientPrice(): CarBidPriceItem
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
        return new static(
            supplierPrice: CarBidPriceItem::deserialize($payload['supplierPrice']),
            clientPrice: CarBidPriceItem::deserialize($payload['clientPrice']),
        );
    }

    /**
     * @param static $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        if (!$b instanceof CarBidPrices) {
            return $this === $b;
        }

        return $this->supplierPrice->isEqual($b->supplierPrice)
            && $this->clientPrice->isEqual($b->clientPrice);
    }
}
