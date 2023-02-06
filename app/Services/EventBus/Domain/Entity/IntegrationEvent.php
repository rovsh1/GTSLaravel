<?php

namespace GTS\Services\EventBus\Domain\Entity;

use GTS\Shared\Domain\Event\DomainEventInterface;

class IntegrationEvent
{
    public function __construct(
        public readonly string $module,
        public readonly DomainEventInterface $event
    ) {}
}
