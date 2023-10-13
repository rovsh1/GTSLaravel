<?php

namespace Sdk\Module\Contracts\Event;

interface IntegrationEventSubscriberInterface
{
    public function listen(string $eventClass, string $listenerClass): void;

    public function handle(IntegrationEventInterface $event): void;
}
