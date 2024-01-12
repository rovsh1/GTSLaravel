<?php

namespace Pkg\Booking\Requesting\Domain\Listener;

use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Pkg\Booking\Requesting\Domain\Event\BookingRequestEventInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class BookingToWaitingConfirmationListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof BookingRequestEventInterface);

        $booking = $this->bookingUnitOfWork->findOrFail($event->bookingId());
        $booking->toWaitingConfirmation();
        $this->bookingUnitOfWork->commit();
    }
}
