<?php

namespace Module\HotelOld\Domain\Event;

class QuotaSold implements QuotaEventInterface
{
    public function __construct(int $roomId, \DateTime $date, int $count) {}
}
