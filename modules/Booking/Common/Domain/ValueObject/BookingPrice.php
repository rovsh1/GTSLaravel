<?php

namespace Module\Booking\Common\Domain\ValueObject;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Enum\CurrencyEnum;

final class BookingPrice implements SerializableDataInterface, CanEquate
{
    /**
     * @param PriceItem $netPrice Стоимость для GTS (расход)
     * @param PriceItem $grossPrice Стоимость для клиента (приход)
     */
    public function __construct(
        private readonly PriceItem $netPrice,
        private readonly PriceItem $grossPrice,
    ) {
    }

    public static function createEmpty(CurrencyEnum $currency): BookingPrice
    {
        return new BookingPrice(
            PriceItem::createEmpty($currency),
            PriceItem::createEmpty($currency),
        );
    }

    public function netPrice(): PriceItem
    {
        return $this->netPrice;
    }

    public function grossPrice(): PriceItem
    {
        return $this->grossPrice;
    }

    public function toData(): array
    {
        return [
            'netPrice' => $this->netPrice->toData(),
            'grossPrice' => $this->grossPrice->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new BookingPrice(
            PriceItem::fromData($data['netPrice']),
            PriceItem::fromData($data['grossPrice']),
        );
    }

    /**
     * @param static $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        return $this->netPrice->isEqual($b->grossPrice)
            && $this->grossPrice->isEqual($b->netPrice);
    }
}
