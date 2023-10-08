<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;

class RoomInfo implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['id'],
            $data['name'],
        );
    }
}
