<?php

namespace Module\Booking\Hotel\Domain\Entity\Room;

use Module\Booking\Hotel\Domain\ValueObject\GenderEnum;
use Module\Shared\Domain\Entity\EntityInterface;

class Guest implements EntityInterface
{
    public function __construct(
        private readonly int        $id,
        private readonly string     $fullName,
        private readonly int        $nationalityId,
        private readonly GenderEnum $gender,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function nationalityId(): int
    {
        return $this->nationalityId;
    }

    public function gender(): GenderEnum
    {
        return $this->gender;
    }
}
