<?php

namespace Module\Catalog\Domain\Hotel\Event;

class QuotaAdded implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date, int $count) {}
}
