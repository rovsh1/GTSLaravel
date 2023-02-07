<?php

namespace Custom\Framework\Event;

use Custom\Framework\Contracts\Event\DomainEventInterface;

class IntegrationEvent
{
    public function __construct(
        public readonly string $module,
        public readonly DomainEventInterface $event
    ) {}
}
