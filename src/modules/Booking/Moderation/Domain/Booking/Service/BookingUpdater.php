<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Booking\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class BookingUpdater
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly BookingRepositoryInterface $repository,
    ) {}

    public function store(Booking $booking): bool
    {
        $success = $this->repository->store($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());

        return $success;
    }

    /**
     * @param Booking $booking
     * @return bool
     * @todo подумать как точно провернить, что были изменения в броне
     */
    public function storeIfHasEvents(Booking $booking): bool
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
