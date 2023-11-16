<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Service;

use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class BookingUnitOfWork implements BookingUnitOfWorkInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly DomainEventDispatcherInterface $domainEventDispatcher,
    ) {
    }

    public function commit(): void
    {
        foreach ($this->bookingRepository->get() as $booking) {
            $this->bookingRepository->store($booking);
            $this->domainEventDispatcher->dispatch(...$booking->pullEvents());
        }

        foreach ($this->detailsRepository->get() as $details) {
            $this->detailsRepository->store($details);
//            $this->domainEventDispatcher->dispatch(...$booking->pullEvents());
        }

        foreach ($this->accommodationRepository->get() as $accommodation) {
            $this->accommodationRepository->store($accommodation);
//            $this->domainEventDispatcher->dispatch(...$booking->pullEvents());
        }
        //@todo catch changes, add events
    }

    public function bookingRepository(): BookingRepositoryInterface
    {
        return $this->bookingRepository;
    }

    public function detailsRepository(): DetailsRepositoryInterface
    {
        return $this->detailsRepository;
    }
}
