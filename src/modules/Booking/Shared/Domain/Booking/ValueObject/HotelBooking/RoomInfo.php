<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableInterface;

class RoomInfo implements ValueObjectInterface, SerializableInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly int $guestsCount,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function guestsCount(): int
    {
        return $this->guestsCount;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guestsCount' => $this->guestsCount,
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            $payload['id'],
            $payload['name'],
            $payload['guestsCount'],
        );
    }
}
