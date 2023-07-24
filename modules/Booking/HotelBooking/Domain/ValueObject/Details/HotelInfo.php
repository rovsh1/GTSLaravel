<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\ValueObject\Details;

use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\Time;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class HotelInfo implements ValueObjectInterface, SerializableDataInterface
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

    public function toData(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'checkInTime' => $this->checkInTime->value(),
            'checkOutTime' => $this->checkOutTime->value()
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['id'],
            $data['name'],
            new Time($data['checkInTime']),
            new Time($data['checkOutTime']),
        );
    }
}
