<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Entity\Details;

use Illuminate\Support\Collection;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

/**
 * @extends Collection<int, Room>
 */
final class RoomCollection extends Collection implements SerializableDataInterface
{
    public function toData(): array
    {
        return $this->map(fn(Room $condition) => $condition->toData())->values()->all();
    }

    public static function fromData(array $data): static
    {
        return (new static($data))->map(fn(array $item) => Room::fromData($item));
    }
}
