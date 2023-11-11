<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation;

use Module\Booking\Moderation\Domain\Booking\Event\HotelBooking\AccommodationDeleted;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationId;
use Module\Shared\Contracts\Service\SafeExecutorInterface;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

final class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly SafeExecutorInterface $executor,
    ) {
    }

    public function execute(int $bookingId, int $accommodationId): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));

        $this->executor->execute(function () use ($booking, $accommodationId) {
            $accommodationId = new AccommodationId($accommodationId);
            $this->accommodationRepository->delete($accommodationId);
            $this->eventDispatcher->dispatch(new AccommodationDeleted($booking, $accommodationId));
        });
    }
}
