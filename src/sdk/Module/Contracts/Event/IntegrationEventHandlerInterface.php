<?php

namespace Sdk\Module\Contracts\Event;

interface IntegrationEventHandlerInterface
{
    public function handle(IntegrationEventInterface $event);
}
