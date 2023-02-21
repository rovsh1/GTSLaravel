<?php

namespace Module\Hotel\Domain\Entity;

use Custom\Framework\Support\DateTimeInterface;

class RoomQuota
{
    public function __construct(
        private readonly int $id,
        private readonly int $roomId,
        private DateTimeInterface $date,
        private bool $status,
        private int $releaseDays
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
}
