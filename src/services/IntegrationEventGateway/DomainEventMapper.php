<?php

namespace Service\IntegrationEventGateway;

use Module\Booking\Domain\Shared\Event\BookingCreated;
use Module\Shared\Contracts\Event\IntegrationEventInterface;
use Module\Shared\IntegrationEvent;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class DomainEventMapper
{
    public function map(DomainEventInterface $domainEvent): IntegrationEventInterface
    {
        return match ($domainEvent::class) {
            BookingCreated::class => new IntegrationEvent\Booking\BookingCreated($domainEvent->bookingId())
        };
    }
}