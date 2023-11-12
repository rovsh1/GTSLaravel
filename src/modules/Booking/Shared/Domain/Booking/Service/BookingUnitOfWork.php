<?php

namespace Module\Booking\Shared\Domain\Booking\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Shared\Domain\Booking\Event\BookingModified;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\IntegrationEventPublisherInterface;

class BookingUnitOfWork
{
    private Booking $booking;

    private array $originalData;

    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly IntegrationEventPublisherInterface $integrationEventPublisher,
    ) {
    }

    public function findOrFail(BookingId $id): Booking
    {
        $booking = $this->bookingRepository->findOrFail($id);

        $this->originalData = $booking->toData();

        return $this->booking = $booking;
    }

    public function details(ServiceDetailsInterface $details): void
    {
    }

    public function commit(): void
    {
        $this->bookingRepository->store($this->booking);
        $this->eventDispatcher->dispatch(...$this->booking->pullEvents());

        $events = [];
        $currentData = $this->booking->toData();
        if (!$this->isDataEqual($this->originalData, $currentData)) {
            $events[] = new BookingModified($this->booking, $this->originalData);
        }

        $this->integrationEventPublisher->publish(...$events);
    }

    private function isDataEqual(array $dataA, array $dataB): bool
    {
        return json_encode($dataA) === json_encode($dataB);
    }
}