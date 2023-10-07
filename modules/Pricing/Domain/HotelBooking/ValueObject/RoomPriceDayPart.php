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
        private readonly string $formula,
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

    public function formula(): string
    {
        return $this->formula;
    }

    public function toData(): array
    {
        return [
            'date' => $this->date->getTimestamp(),
            'value' => $this->value,
            'formula' => $this->formula,
        ];
    }

    public static function fromData(array $data): static
    {
        return new RoomPriceDayPart(
            Date::createFromTimestamp($data['date']),
            $data['value'],
            $data['formula'],
        );
    }
}
