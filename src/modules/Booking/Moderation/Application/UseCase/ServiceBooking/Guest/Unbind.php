<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\Guest;

use Module\Booking\Moderation\Domain\Booking\Event\GuestUnbinded;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Contracts\Entity\AirportDetailsInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Unbind implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $bookingId, int $guestId): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));
        $details = $this->detailsRepository->findOrFail($booking->id());
        assert($details instanceof AirportDetailsInterface);
        $guest = $this->guestRepository->findOrFail(new GuestId($guestId));
        $details->removeGuest($guest->id());
        $this->detailsRepository->store($details);
        $this->eventDispatcher->dispatch(new GuestUnbinded($booking, $guest));
    }
}
