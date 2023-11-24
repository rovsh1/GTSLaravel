<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableInterface;
use Sdk\Module\Support\AbstractValueObjectCollection;

final class RoomPriceDayPartCollection extends AbstractValueObjectCollection implements ValueObjectInterface,
                                                                                        SerializableInterface
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
