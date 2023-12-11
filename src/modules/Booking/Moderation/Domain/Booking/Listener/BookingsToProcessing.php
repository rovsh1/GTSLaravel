<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Booking\Listener;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Module\Booking\Shared\Domain\Order\Event\OrderGuestEventInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class BookingsToProcessing implements DomainEventListenerInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork
    ) {}

    public function handle(DomainEventInterface $event)
    {
        assert($event instanceof OrderGuestEventInterface);

        $bookings = $this->bookingRepository->getByGuestId($event->guestId());
        foreach ($bookings as $booking) {
            $this->bookingUnitOfWork->persist($booking);
            $booking->toProcessing();
        }
        $this->bookingUnitOfWork->commit();
    }
}
