<?php

namespace GTS\Shared\Application\Event;

use GTS\Shared\Domain\Event\DomainEventInterface;

interface DomainEventListenerInterface
{
    public function handle(DomainEventInterface $event);
}
