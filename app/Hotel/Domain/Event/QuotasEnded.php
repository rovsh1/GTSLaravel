<?php

namespace GTS\Hotel\Domain\Event;

use Custom\Framework\Contracts\Event\DomainEventInterface;

class QuotasEnded implements DomainEventInterface
{
    public function __construct(int $roomId) { }
}
