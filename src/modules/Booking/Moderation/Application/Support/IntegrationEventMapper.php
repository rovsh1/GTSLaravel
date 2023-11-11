<?php

namespace Module\Booking\Moderation\Application\Support;

use Module\Booking\Application\UseCase\Admin\TestEvent;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventMapperInterface;

class IntegrationEventMapper implements IntegrationEventMapperInterface
{
    public function map(DomainEventInterface $domainEvent): array
    {
        return [];
//        match ($domainEvent::class) {
//            TestEvent::class => new \Module\Booking\Shared\Application\Event\TestEvent($domainEvent->bookingId),
//            default => null
//        };
    }
}