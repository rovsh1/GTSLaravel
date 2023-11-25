<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\ValueObject\Date;

final class RoomPriceDayPart implements ValueObjectInterface, SerializableInterface
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

    public function serialize(): array
    {
        return [
            'date' => $this->date->getTimestamp(),
            'value' => $this->value,
            'formula' => $this->formula,
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new RoomPriceDayPart(
            Date::createFromTimestamp($payload['date']),
            $payload['value'],
            $payload['formula'],
        );
    }
}
