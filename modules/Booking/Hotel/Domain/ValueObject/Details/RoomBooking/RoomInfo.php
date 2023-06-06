<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

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
