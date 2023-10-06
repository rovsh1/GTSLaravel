<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\HotelBooking\ValueObject;

use Module\Shared\Domain\ValueObject\Date;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

final class RoomPriceDayPart implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly Date $date,
        private readonly int|float $value,
    ) {
    }

    public function date(): Date
    {
        return $this->date;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function toData(): array
    {
        return [
            'date' => $this->date->getTimestamp(),
            'value' => $this->value,
        ];
    }

    public static function fromData(array $data): static
    {
        return new RoomPriceDayPart(
            Date::createFromTimestamp($data['date']),
            $data['value'],
        );
    }
}
