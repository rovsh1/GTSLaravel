<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject\Details;

use Illuminate\Support\Collection;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

/**
 * @extends Collection<int, RoomBooking>
 */
final class RoomBookingCollection extends Collection implements SerializableDataInterface
{
    public function toData(): array
    {
        return $this->map(fn(RoomBooking $roomBooking) => $roomBooking->toData())->values()->all();
    }

    public static function fromData(array $data): static
    {
        return (new static($data))->map(fn(array $item) => RoomBooking::fromData($item));
    }
}
