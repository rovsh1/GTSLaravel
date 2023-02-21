<?php

namespace Module\Hotel\Domain\Event;

class QuotaRemoved implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date, int $count) {}
}
