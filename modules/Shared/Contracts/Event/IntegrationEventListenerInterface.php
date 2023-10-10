<?php

namespace Module\Shared\Contracts\Event;

interface IntegrationEventListenerInterface
{
    public function handle(IntegrationEventInterface $event): void;
}
