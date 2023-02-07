<?php

namespace GTS\Hotel\Domain\Event;

use Custom\Framework\Event\DomainEventInterface;

class QuotasEnded implements DomainEventInterface
{
    public function __construct(int $roomId) { }
}
