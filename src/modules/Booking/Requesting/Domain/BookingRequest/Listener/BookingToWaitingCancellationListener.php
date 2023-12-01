<?php

namespace Module\Booking\Requesting\Domain\BookingRequest\Listener;

use Module\Booking\Requesting\Domain\BookingRequest\Event\BookingRequestEventInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class BookingToWaitingCancellationListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof BookingRequestEventInterface);

        $booking = $this->bookingUnitOfWork->findOrFail($event->bookingId());
        $booking->toWaitingCancellation();
        $this->bookingUnitOfWork->commit();
    }
}
