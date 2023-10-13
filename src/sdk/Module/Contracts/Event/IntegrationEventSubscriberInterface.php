<?php

namespace Sdk\Module\Contracts\Event;

use Module\Shared\Contracts\Event\IntegrationEventInterface;

interface IntegrationEventSubscriberInterface
{
    public function listen(string $eventClass, string $listenerClass): void;

    public function handle(IntegrationEventInterface $event): void;
}
