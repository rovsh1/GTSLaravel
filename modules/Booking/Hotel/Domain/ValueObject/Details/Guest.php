<?php

namespace Module\Booking\Hotel\Domain\ValueObject\Details;

use Module\Shared\Domain\ValueObject\GenderEnum;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

final class Guest implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly string $fullName,
        private readonly int $countryId,
        private readonly GenderEnum $gender,
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

    public function toData(): array
    {
        return [
            'fullName' => $this->fullName,
            'countryId' => $this->countryId,
            'gender' => $this->gender->value
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['fullName'],
            $data['countryId'],
            GenderEnum::from($data['gender']),
        );
    }
}
