<?php

namespace Module\Booking\Domain\Booking\Listener;

use Module\Booking\Domain\Shared\Event\BookingEventInterface;
use Module\Booking\Domain\Shared\Repository\BookingChangesLogRepositoryInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class BookingChangesListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly BookingChangesLogRepositoryInterface $changesLogRepository
    ) {}

    public function handle(DomainEventInterface|BookingEventInterface $event)
    {
        $this->changesLogRepository->logBookingChange($event);
    }
}
