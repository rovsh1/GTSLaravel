<?php

namespace Sdk\Module\Contracts\Event;

interface IntegrationEventListenerInterface
{
    public function handle(IntegrationEventInterface $event): void;
}
