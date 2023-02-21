<?php

namespace Module\Hotel\Domain\Event;

class QuotaAdded implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date, int $count) {}
}
