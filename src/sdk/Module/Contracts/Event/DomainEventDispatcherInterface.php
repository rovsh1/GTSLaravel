<?php

namespace Sdk\Module\Contracts\Event;

interface DomainEventDispatcherInterface
{
    public function dispatch(DomainEventInterface ...$events): void;
}
