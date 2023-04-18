<?php

namespace Module\Booking\Hotel\Domain\ValueObject;

use Module\Shared\Domain\Entity\EntityInterface;

class Hotel implements EntityInterface
{
    public function __construct(
        private readonly int $id,
        private ?string      $checkInTime = null,
        private ?string      $checkOutTime = null,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function checkInTime(): ?string
    {
        return $this->checkInTime;
    }

    public function checkOutTime(): ?string
    {
        return $this->checkOutTime;
    }
}
