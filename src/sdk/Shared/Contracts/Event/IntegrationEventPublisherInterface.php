<?php

namespace Sdk\Shared\Contracts\Event;

interface IntegrationEventPublisherInterface
{
    public function publish(string $originator, IntegrationEventInterface $event, array $context): void;
}
