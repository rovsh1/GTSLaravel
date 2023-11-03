<?php

namespace Module\Catalog\Domain\Hotel\Entity;

use DateTimeInterface;

class RoomQuota
{
    public function __construct(
        private readonly int $id,
        private readonly int $roomId,
        private DateTimeInterface $date,
        private bool $status,
        private int $releaseDays,
        private int $countTotal,
        private int $countAvailable,
        private int $countBooked,
        private int $countReserved
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function roomId(): int
    {
        return $this->roomId;
    }

    public function date(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function status(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function releaseDays(): int
    {
        return $this->releaseDays;
    }

    public function setReleaseDays(int $days): void
    {
        $this->releaseDays = $days;
    }

    public function countTotal(): int
    {
        return $this->countTotal;
    }

    public function countAvailable(): int
    {
        return $this->countAvailable;
    }

    public function countBooked(): int
    {
        return $this->countBooked;
    }

    public function countReserved(): int
    {
        return $this->countReserved;
    }
}