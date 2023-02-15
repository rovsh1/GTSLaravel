<?php

namespace GTS\Hotel\Domain\Event;

class QuotaSold implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date, int $count) {}
}
