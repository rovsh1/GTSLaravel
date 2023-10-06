<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\HotelBooking\ValueObject;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class RoomPriceItem implements SerializableDataInterface, CanEquate
{
    public function __construct(
        private readonly RoomPriceDayPartCollection $priceParts,
        private readonly ?float $dayValue,
    ) {
    }

    public static function createEmpty(): RoomPriceItem
    {
        return new RoomPriceItem(new RoomPriceDayPartCollection(), null);
    }

    public function value(): float
    {
        $sum = 0.0;
        foreach ($this->priceParts as $item) {
            $sum += $item->value();
        }

        return $sum;
    }

    public function dayValue(): ?float
    {
        return $this->dayValue;
    }

    public function priceParts(): RoomPriceDayPartCollection
    {
        return $this->priceParts;
    }

    public function toData(): array
    {
        return [
            'priceParts' => $this->priceParts->toData(),
            'dayValue' => $this->dayValue
        ];
    }

    public static function fromData(array $data): static
    {
        return new RoomPriceItem(
            priceParts: RoomPriceDayPartCollection::fromData($data['priceParts']),
            dayValue: $data['dayValue'],
        );
    }

    public function isEqual(mixed $b): bool
    {
        // TODO: Implement isEqual() method.
    }
}
