<?php

namespace Sdk\Module\Contracts\Event;

interface DomainEventPublisherInterface
{
    public function publish(DomainEventInterface ...$events): void;
}
