<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation;

use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Event\HotelBooking\AccommodationDeleted;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

final class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $bookingId, int $accommodationId): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));
        $this->bookingUnitOfWork->touch($booking->id());
        $this->bookingUnitOfWork->commiting(function () use ($booking, $accommodationId) {
            $accommodationId = new AccommodationId($accommodationId);
            $this->accommodationRepository->delete($accommodationId);
            $this->eventDispatcher->dispatch(new AccommodationDeleted($booking, $accommodationId));
        });
    }
}
