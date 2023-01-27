<?php

namespace GTS\Hotel\Domain\Event;

use GTS\Shared\Domain\Event\EventInterface;

class QuotasEnded implements EventInterface
{
    public function __construct(int $roomId) { }
}
