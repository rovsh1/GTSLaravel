<?php

namespace GTS\Hotel\Domain\Event;

class QuotaReserved implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date, int $count) {}
}
