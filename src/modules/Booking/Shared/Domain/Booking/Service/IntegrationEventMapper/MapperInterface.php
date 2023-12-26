<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper;

use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

interface MapperInterface
{
    /**
     * @param DomainEventInterface $domainEvent
     * @return IntegrationEventInterface[]
     */
    public function map(DomainEventInterface $event): array;
}