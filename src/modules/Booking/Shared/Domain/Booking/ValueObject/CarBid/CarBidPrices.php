<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject\CarBid;

use Sdk\Shared\Enum\CurrencyEnum;

class CarBidPrices
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

    public function toData(): array
    {
        return [
            'supplierPrice' => $this->supplierPrice->serialize(),
            'clientPrice' => $this->clientPrice->serialize(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            supplierPrice: CarBidPriceItem::deserialize($data['supplierPrice']),
            clientPrice: CarBidPriceItem::deserialize($data['clientPrice']),
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
