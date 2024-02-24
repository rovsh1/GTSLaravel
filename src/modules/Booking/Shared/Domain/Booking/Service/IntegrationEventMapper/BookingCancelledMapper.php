<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper;

use Sdk\Booking\Event\BookingCancelledEventInterface;
use Sdk\Booking\IntegrationEvent\BookingCancelled;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class BookingCancelledMapper implements MapperInterface
{
    public function map(DomainEventInterface $event): array
    {
        assert($event instanceof BookingCancelledEventInterface);

        return [
            new BookingCancelled(
                $event->bookingId()->value(),
                $event->status()
            ),
        ];
    }
}
