<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;

class RoomInfo implements ValueObjectInterface, SerializableDataInterface
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

    public function toData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guestsCount' => $this->guestsCount,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['id'],
            $data['name'],
            $data['guestsCount'],
        );
    }
}
