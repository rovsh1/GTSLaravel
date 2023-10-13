<?php

namespace Sdk\Module\Contracts\Event;

interface DomainEventPublisherInterface
{
    public function registerMapper(IntegrationEventMapperInterface $integrationEventMapper): void;

    public function publish(DomainEventInterface ...$events): void;
}
