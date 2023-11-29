<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Guest;

use Module\Shared\Contracts\Domain\EntityInterface;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Shared\Contracts\Support\SerializableInterface;
use Sdk\Shared\Enum\GenderEnum;

final class Guest extends AbstractAggregateRoot implements EntityInterface, SerializableInterface
{
    public function __construct(
        private readonly GuestId $id,
        private readonly OrderId $orderId,
        private string $fullName,
        private int $countryId,
        private GenderEnum $gender,
        private bool $isAdult,
        private ?int $age,
    ) {}

    public function id(): GuestId
    {
        return $this->id;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

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

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function setCountryId(int $countryId): void
    {
        $this->countryId = $countryId;
    }

    public function setGender(GenderEnum $gender): void
    {
        $this->gender = $gender;
    }

    public function setIsAdult(bool $isAdult): void
    {
        $this->isAdult = $isAdult;
    }

    public function setAge(?int $age): void
    {
        $this->age = $age;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'orderId' => $this->orderId->value(),
            'fullName' => $this->fullName,
            'countryId' => $this->countryId,
            'gender' => $this->gender->value,
            'isAdult' => $this->isAdult,
            'age' => $this->age
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new Guest(
            new GuestId($payload['id']),
            new OrderId($payload['orderId']),
            $payload['fullName'],
            $payload['countryId'],
            GenderEnum::from($payload['gender']),
            $payload['isAdult'],
            $payload['age'],
        );
    }
}
