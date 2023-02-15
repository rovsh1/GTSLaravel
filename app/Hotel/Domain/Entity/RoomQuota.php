<?php

namespace GTS\Hotel\Domain\Entity;

use Custom\Framework\Support\DateTimeInterface;

class RoomQuota
{
    public function __construct(
        public readonly int $roomId,
        public readonly DateTimeInterface $date,
        public readonly bool $status,
        public readonly int $releaseDays,
//        public int $revision = 0,
    ) {}
}
