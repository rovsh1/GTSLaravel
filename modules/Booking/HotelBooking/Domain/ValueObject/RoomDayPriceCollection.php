<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;
use Sdk\Module\Support\AbstractValueObjectCollection;

final class RoomDayPriceCollection extends AbstractValueObjectCollection implements ValueObjectInterface,
                                                                                    SerializableDataInterface
{
    public function validateItem(mixed $item): void
    {
        if (!$item instanceof RoomDayPrice) {
            throw new \InvalidArgumentException(RoomDayPrice::class . ' instance required');
        }
    }

    public function toData(): array
    {
        return array_map(fn(RoomDayPrice $p) => $p->toData(), $this->items);
    }

    public static function fromData(array $data): static
    {
        return new RoomDayPriceCollection(array_map(fn(array $r) => RoomDayPrice::fromData($r), $data));
    }
}
