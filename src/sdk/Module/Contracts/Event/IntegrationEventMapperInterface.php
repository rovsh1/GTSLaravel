<?php

namespace Sdk\Module\Contracts\Event;

interface IntegrationEventMapperInterface
{
    public function map(DomainEventInterface $domainEvent): ?IntegrationEventInterface;
}
