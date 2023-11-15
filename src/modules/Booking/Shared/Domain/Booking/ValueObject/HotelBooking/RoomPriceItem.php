<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking;

use Module\Shared\Contracts\CanEquate;
use Module\Shared\Contracts\Support\SerializableDataInterface;

final class RoomPriceItem implements SerializableDataInterface, CanEquate
{
    public function __construct(
        private readonly RoomPriceDayPartCollection $dayParts,
        private readonly ?float $manualDayValue,
    ) {}

    public static function createEmpty(): RoomPriceItem
    {
        return new RoomPriceItem(new RoomPriceDayPartCollection(), null);
    }

    public function value(): float
    {
        $sum = 0.0;
        foreach ($this->dayParts as $item) {
            $sum += $item->value();
        }

        return $sum;
    }

    public function manualValue(): ?float
    {
        if ($this->manualDayValue === null) {
            return null;
        }
        $sum = 0.0;
        foreach ($this->dayParts as $item) {
            $sum += $this->manualDayValue;
        }

        return $sum;
    }

    public function manualDayValue(): ?float
    {
        return $this->manualDayValue;
    }

    public function dayParts(): RoomPriceDayPartCollection
    {
        return $this->dayParts;
    }

    public function toData(): array
    {
        return [
            'dayParts' => $this->dayParts->toData(),
            'manualDayValue' => $this->manualDayValue
        ];
    }

    public static function fromData(array $data): static
    {
        return new RoomPriceItem(
            dayParts: RoomPriceDayPartCollection::fromData($data['dayParts']),
            manualDayValue: $data['manualDayValue'],
        );
    }

    public function isEqual(mixed $b): bool
    {
        // TODO: Implement isEqual() method.
    }
}
