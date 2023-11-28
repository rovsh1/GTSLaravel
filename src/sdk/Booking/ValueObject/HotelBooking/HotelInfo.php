<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject\HotelBooking;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\ValueObject\Time;

class HotelInfo implements ValueObjectInterface, SerializableInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        public readonly Time $checkInTime,
        public readonly Time $checkOutTime,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function checkInTime(): Time
    {
        return $this->checkInTime;
    }

    public function checkOutTime(): Time
    {
        return $this->checkOutTime;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'checkInTime' => $this->checkInTime->value(),
            'checkOutTime' => $this->checkOutTime->value()
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            $payload['id'],
            $payload['name'],
            new Time($payload['checkInTime']),
            new Time($payload['checkOutTime']),
        );
    }
}