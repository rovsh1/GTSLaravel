<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject;

use Illuminate\Support\Collection;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

/**
 * @extends Collection<int, DayPrice>
 */
class DayPriceCollection extends Collection implements SerializableDataInterface
{
    public function toData(): array
    {
        return $this->map(fn(DayPrice $roomBooking) => $roomBooking->toData())->values()->all();
    }

    public static function fromData(array $data): static
    {
        return (new static($data))->map(fn(array $item) => DayPrice::fromData($item));
    }
}
