<?php

namespace Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking;

use Module\Shared\Domain\ValueObject\GenderEnum;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

final class Guest implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly string $fullName,
        private readonly int $countryId,
        private readonly GenderEnum $gender,
        private readonly bool $isAdult,
        private readonly ?int $age,
    ) {}

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function countryId(): int
    {
        return $this->countryId;
    }

    public function gender(): GenderEnum
    {
        return $this->gender;
    }

    public function isAdult(): bool
    {
        return $this->isAdult;
    }

    public function age(): ?int
    {
        return $this->age;
    }

    public function toData(): array
    {
        return [
            'fullName' => $this->fullName,
            'countryId' => $this->countryId,
            'gender' => $this->gender->value,
            'isAdult' => $this->isAdult,
            'age' => $this->age,
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['fullName'],
            $data['countryId'],
            GenderEnum::from($data['gender']),
            $data['isAdult'],
            $data['age'],
        );
    }
}
