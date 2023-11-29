<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation;

use Module\Booking\Moderation\Domain\Booking\Event\HotelBooking\GuestUnbinded;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UnbindGuest implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $bookingId, int $accommodationId, int $guestId): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));
        $accommodation = $this->accommodationRepository->findOrFail(new AccommodationId($accommodationId));
        $guest = $this->guestRepository->findOrFail(new GuestId($guestId));
        $accommodation->removeGuest($guest->id());
        $this->accommodationRepository->store($accommodation);
        $this->eventDispatcher->dispatch(new GuestUnbinded($booking, $accommodation->id(), $guest));
    }
}
