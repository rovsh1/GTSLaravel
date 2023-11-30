<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject\HotelBooking;

use Sdk\Module\Support\AbstractValueObjectCollection;
use Sdk\Shared\Contracts\Support\SerializableInterface;

final class RoomPriceDayPartCollection extends AbstractValueObjectCollection implements SerializableInterface
{
    public function validateItem(mixed $item): void
    {
        if (!$item instanceof RoomPriceDayPart) {
            throw new \InvalidArgumentException(RoomPriceDayPart::class . ' instance required');
        }
    }

    public function serialize(): array
    {
        return array_map(fn(RoomPriceDayPart $p) => $p->serialize(), $this->items);
    }

    public static function deserialize(array $payload): static
    {
        return new RoomPriceDayPartCollection(array_map(fn(array $r) => RoomPriceDayPart::deserialize($r), $payload));
    }
}
