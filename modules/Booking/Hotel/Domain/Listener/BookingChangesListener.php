<?php

namespace Module\Booking\Hotel\Domain\Listener;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Repository\BookingChangesLogRepositoryInterface;
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
