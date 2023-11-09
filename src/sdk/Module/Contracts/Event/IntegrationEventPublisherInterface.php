<?php

namespace Sdk\Module\Contracts\Event;

interface IntegrationEventPublisherInterface
{
    public function publish(IntegrationEventInterface ...$events): void;
}
