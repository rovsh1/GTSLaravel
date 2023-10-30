<?php

namespace Module\Booking\Application\Admin\Shared\Support;

use Module\Booking\Application\Admin\Booking\UseCase\TestEvent;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventMapperInterface;

class IntegrationEventMapper implements IntegrationEventMapperInterface
{
    public function map(DomainEventInterface $domainEvent): ?IntegrationEventInterface
    {
        return match ($domainEvent::class) {
            TestEvent::class => new \Module\Booking\Application\Admin\Shared\Event\TestEvent($domainEvent->bookingId),
            default => null
        };
    }
}