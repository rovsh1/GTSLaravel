<?php

namespace Module\Catalog\Domain\Hotel\Event;

class QuotaRemoved implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date, int $count) {}
}
