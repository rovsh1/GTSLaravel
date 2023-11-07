<?php

namespace Module\Booking\Shared\Domain\Booking\Listener;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Event\BookingRequestEventInterface;
use Module\Booking\Shared\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class BookingToWaitingCancellationListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly BookingUpdater $bookingUpdater
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof BookingRequestEventInterface);

        $booking = $this->bookingRepository->findOrFail($event->bookingId());
        $booking->toWaitingCancellation();
        $this->bookingUpdater->storeIfHasEvents($booking);
    }
}
