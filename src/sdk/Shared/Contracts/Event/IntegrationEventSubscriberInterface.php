<?php

namespace Sdk\Shared\Contracts\Event;

use Sdk\Shared\Event\IntegrationEventMessage;

interface IntegrationEventSubscriberInterface
{
    public function listen(string $eventClass, string $listenerClass): void;

    public function handle(IntegrationEventMessage $message): void;
}
