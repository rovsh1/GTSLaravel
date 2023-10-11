<?php

namespace Module\Shared\Contracts\Event;

interface IntegrationEventSubscriberInterface
{
    public function handle(IntegrationEventInterface $event): void;
}
