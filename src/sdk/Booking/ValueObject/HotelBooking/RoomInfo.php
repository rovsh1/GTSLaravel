<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject\HotelBooking;

use Sdk\Shared\Contracts\Support\SerializableInterface;

class RoomInfo implements SerializableInterface
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
