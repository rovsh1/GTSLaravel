<?php

namespace Module\Catalog\Domain\Hotel\Event;

class QuotaSold implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date, int $count) {}
}
