<?php

namespace Module\Hotel\Domain\ValueObject\RoomPrice;

use Custom\Framework\Support\DateTimeInterface;

class Identifier
{
    public function __construct(
//        private readonly int $id,
        private readonly int $roomId,
        private readonly DateTimeInterface $date,
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
}
