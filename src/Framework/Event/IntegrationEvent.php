<?php

namespace Custom\Framework\Event;

class IntegrationEvent
{
    public function __construct(
        public readonly string $module,
        public readonly DomainEventInterface $event
    ) {}
}
