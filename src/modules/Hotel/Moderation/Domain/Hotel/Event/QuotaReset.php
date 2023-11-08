<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Event;

class QuotaReset implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date) {}
}
