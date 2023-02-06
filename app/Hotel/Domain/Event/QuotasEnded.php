<?php

namespace GTS\Hotel\Domain\Event;

use GTS\Shared\Domain\Event\DomainEventInterface;

class QuotasEnded implements DomainEventInterface
{
    public function __construct(int $roomId) { }
}
