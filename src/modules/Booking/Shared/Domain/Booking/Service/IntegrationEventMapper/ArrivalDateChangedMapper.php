<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper;

use Sdk\Booking\Event\ArrivalDateChanged;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventInterface;

class ArrivalDateChangedMapper implements MapperInterface
{
    /**
     * @param DomainEventInterface $domainEvent
     * @return IntegrationEventInterface[]
     */
    public function map(DomainEventInterface $event): array
    {
        assert($event instanceof ArrivalDateChanged);

        return [
            new \Sdk\Booking\IntegrationEvent\ArrivalDateChanged($event->bookingId()->value())
        ];
    }
}
