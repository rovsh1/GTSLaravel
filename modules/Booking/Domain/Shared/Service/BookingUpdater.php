<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Service;

use Module\Booking\Deprecated\AirportBooking\AirportBooking;
use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class BookingUpdater
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly BookingRepositoryInterface $repository,
    ) {}

    public function store(BookingInterface $booking): bool
    {
        $success = $this->repository->store($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());

        return $success;
    }

    /**
     * @param HotelBooking|AirportBooking $booking
     * @return bool
     * @todo подумать как точно провернить, что были изменения в броне
     */
    public function storeIfHasEvents(BookingInterface $booking): bool
    {
        $events = $booking->pullEvents();
        if (count($events) === 0) {
            return true;
        }
        $success = $this->repository->store($booking);
        $this->eventDispatcher->dispatch(...$events);

        return $success;
    }
}
