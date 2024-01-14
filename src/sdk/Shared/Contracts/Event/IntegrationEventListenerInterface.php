<?php

namespace Sdk\Shared\Contracts\Event;

use Sdk\Shared\Event\IntegrationEventMessage;

interface IntegrationEventListenerInterface
{
    public function handle(IntegrationEventMessage $message): void;
}
