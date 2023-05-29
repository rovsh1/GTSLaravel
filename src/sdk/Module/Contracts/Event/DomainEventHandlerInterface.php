<?php

namespace Sdk\Module\Contracts\Event;

interface DomainEventHandlerInterface
{
    public function handle(DomainEventInterface $event);
}
