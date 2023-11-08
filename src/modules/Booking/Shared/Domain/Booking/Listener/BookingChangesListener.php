<?php

namespace Module\Booking\Shared\Domain\Booking\Listener;

use Module\Booking\ChangeHistory\Domain\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Event\BookingEventInterface;
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
