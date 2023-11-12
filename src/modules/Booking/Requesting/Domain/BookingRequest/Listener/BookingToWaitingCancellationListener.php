<?php

namespace Module\Booking\Requesting\Domain\BookingRequest\Listener;

use Module\Booking\Moderation\Domain\Booking\Service\BookingUpdater;
use Module\Booking\Shared\Domain\Booking\Event\BookingRequestEventInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
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
