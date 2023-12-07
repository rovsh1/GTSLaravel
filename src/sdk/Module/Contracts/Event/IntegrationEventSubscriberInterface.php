<?php

namespace Sdk\Module\Contracts\Event;

use Sdk\Module\Event\IntegrationEventMessage;

interface IntegrationEventSubscriberInterface
{
    public function listen(string $eventClass, string $listenerClass): void;

    public function handle(IntegrationEventMessage $message): void;
}
