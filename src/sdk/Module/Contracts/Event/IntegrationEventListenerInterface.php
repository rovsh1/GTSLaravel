<?php

namespace Sdk\Module\Contracts\Event;

use Sdk\Module\Event\IntegrationEventMessage;

interface IntegrationEventListenerInterface
{
    public function handle(IntegrationEventMessage $message): void;
}
