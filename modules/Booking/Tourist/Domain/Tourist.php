<?php

declare(strict_types=1);

namespace Module\Booking\Tourist\Domain;

use Module\Booking\Common\Domain\ValueObject\TouristId;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\GenderEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Tourist extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly TouristId $id,
        private readonly string $fullName,
        private readonly int $countryId,
        private readonly GenderEnum $gender,
        private readonly bool $isAdult,
        private readonly ?int $age,
    ) {}

    public function id(): TouristId
    {
        return $this->id;
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
}
