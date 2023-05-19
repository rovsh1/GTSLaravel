<?php

namespace Module\Booking\Hotel\Domain\Entity\Details;

use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Time;

final class Hotel implements EntityInterface
{
    public function __construct(
        private readonly int $id,
        private Time $checkInTime,
        private Time $checkOutTime,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function checkInTime(): Time
    {
        return $this->checkInTime;
    }

    public function checkOutTime(): Time
    {
        return $this->checkOutTime;
    }
}
