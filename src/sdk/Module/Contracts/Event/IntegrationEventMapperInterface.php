<?php

namespace Sdk\Module\Contracts\Event;

interface IntegrationEventMapperInterface
{
    /**
     * @param DomainEventInterface $domainEvent
     * @return IntegrationEventInterface[]
     */
    public function map(DomainEventInterface $domainEvent): array;
}
