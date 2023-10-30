<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\ValueObject\HotelBooking;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;
use Sdk\Module\Support\AbstractValueObjectCollection;

final class RoomPriceDayPartCollection extends AbstractValueObjectCollection implements ValueObjectInterface,
                                                                                        SerializableDataInterface
{
    public function validateItem(mixed $item): void
    {
        if (!$item instanceof RoomPriceDayPart) {
            throw new \InvalidArgumentException(RoomPriceDayPart::class . ' instance required');
        }
    }

    public function toData(): array
    {
        return array_map(fn(RoomPriceDayPart $p) => $p->toData(), $this->items);
    }

    public static function fromData(array $data): static
    {
        return new RoomPriceDayPartCollection(array_map(fn(array $r) => RoomPriceDayPart::fromData($r), $data));
    }
}
