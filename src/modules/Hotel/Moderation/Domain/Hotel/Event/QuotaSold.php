<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Event;

class QuotaSold implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date, int $count) {}
}
