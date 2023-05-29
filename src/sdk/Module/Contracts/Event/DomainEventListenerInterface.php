<?php

namespace Sdk\Module\Contracts\Event;

interface DomainEventListenerInterface
{
    public function handle(DomainEventInterface $event);
}
