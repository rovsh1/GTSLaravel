<?php

namespace Sdk\Module\Contracts\Event;

interface DomainEventDispatcherInterface
{
    public function listen(string $eventClass, string $listenerClass): void;

    public function dispatch(DomainEventInterface ...$events): void;
}
