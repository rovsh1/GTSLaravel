<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Entity;

use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\Order\Domain\ValueObject\GuestId;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\GenderEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Guest extends AbstractAggregateRoot implements EntityInterface
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
}
