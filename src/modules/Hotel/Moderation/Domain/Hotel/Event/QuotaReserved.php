<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Event;

class QuotaReserved implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date, int $count) {}
}
