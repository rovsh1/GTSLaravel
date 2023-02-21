<?php

namespace Module\Hotel\Domain\Event;

class QuotaReset implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date) {}
}
