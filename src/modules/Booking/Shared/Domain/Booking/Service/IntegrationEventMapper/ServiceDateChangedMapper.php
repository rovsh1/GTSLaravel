<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper;

use Sdk\Booking\Event\ServiceDateChanged;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventInterface;

class ServiceDateChangedMapper implements MapperInterface
{

    /**
     * @param DomainEventInterface $domainEvent
     * @return IntegrationEventInterface[]
     */
    public function map(DomainEventInterface $event): array
    {
        assert($event instanceof ServiceDateChanged);

        return [
            new \Sdk\Booking\IntegrationEvent\ServiceDateChanged($event->bookingId()->value())
        ];
    }
}
