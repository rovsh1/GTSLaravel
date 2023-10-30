<?php

namespace Module\Catalog\Domain\Hotel\Event;

class QuotaReset implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date) {}
}
