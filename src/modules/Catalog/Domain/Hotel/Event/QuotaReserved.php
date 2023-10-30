<?php

namespace Module\Catalog\Domain\Hotel\Event;

class QuotaReserved implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date, int $count) {}
}
