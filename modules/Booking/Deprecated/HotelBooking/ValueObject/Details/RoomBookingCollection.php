<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\HotelBooking\ValueObject\Details;

use Illuminate\Support\Collection;
use Module\Booking\Deprecated\HotelBooking\Entity\RoomBooking;
use Module\Shared\Contracts\Support\SerializableDataInterface;

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
