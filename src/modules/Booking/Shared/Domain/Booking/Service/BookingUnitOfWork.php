<?php

namespace Module\Booking\Shared\Domain\Booking\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Shared\Domain\Booking\Event\BookingDetailsModified;
use Module\Booking\Shared\Domain\Booking\Event\BookingModified;
use Module\Booking\Shared\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\IntegrationEventPublisherInterface;

class BookingUnitOfWork
{
    private Booking $booking;

    private ServiceDetailsInterface $details;

    private array $originalData;

    private array $originalDetailsData;

    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly IntegrationEventPublisherInterface $integrationEventPublisher,
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
    ) {
    }

    public function findOrFail(BookingId $id): Booking
    {
        $booking = $this->bookingRepository->findOrFail($id);

        $this->originalData = $booking->toData();

        return $this->booking = $booking;
    }

    public function details(): ServiceDetailsInterface
    {
        if (isset($this->details)) {
            return $this->details;
        }

        $detailsRepository = $this->detailsRepositoryFactory->build($this->booking);

        $this->details = $detailsRepository->findOrFail($this->booking->id());

        $this->originalDetailsData = $this->details->toData();

        return $this->details;
    }

    public function commit(): void
    {
        $this->bookingRepository->store($this->booking);
        $this->eventDispatcher->dispatch(...$this->booking->pullEvents());

        $currentData = $this->booking->toData();
        if (!$this->isDataEqual($this->originalData, $currentData)) {
            $this->integrationEventPublisher->publish(
                new BookingModified($this->booking, $this->originalData)
            );
        }

        $this->commitDetails();
    }

    private function commitDetails(): void
    {
        if (!isset($this->details)) {
            return;
        }

        $currentData = $this->details->toData();
        if ($this->isDataEqual($this->originalDetailsData, $currentData)) {
            return;
        }

        $detailsRepository = $this->detailsRepositoryFactory->build($this->booking);
        $detailsRepository->store($this->details);

        $this->integrationEventPublisher->publish(
            new BookingDetailsModified($this->booking, $this->details, $this->originalDetailsData)
        );
    }

    private function isDataEqual(array $dataA, array $dataB): bool
    {
        return json_encode($dataA) === json_encode($dataB);
    }
}