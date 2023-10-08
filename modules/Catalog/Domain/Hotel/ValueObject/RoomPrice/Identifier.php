<?php

namespace Module\Catalog\Domain\Hotel\ValueObject\RoomPrice;

use Sdk\Module\Support\Facades\DateTimeInterface;

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
