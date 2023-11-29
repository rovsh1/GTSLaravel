<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation;

use Module\Booking\Moderation\Application\Exception\TooManyRoomGuestsException;
use Module\Booking\Moderation\Domain\Booking\Event\HotelBooking\GuestBinded;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Entity\BookingDetails\HotelAccommodation;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class BindGuest implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly GuestRepositoryInterface $guestRepository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $bookingId, int $accommodationId, int $guestId): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));
        $accommodation = $this->accommodationRepository->findOrFail(new AccommodationId($accommodationId));
        $guest = $this->guestRepository->findOrFail(new GuestId($guestId));

        $this->ensureGuestsCountAllowed($accommodation);

        $accommodation->addGuest($guest->id());
        $this->accommodationRepository->store($accommodation);

        $this->eventDispatcher->dispatch(new GuestBinded($booking, $accommodation->id(), $guest));
    }

    private function ensureGuestsCountAllowed(HotelAccommodation $accommodation): void
    {
        $hotelRoomSettings = $this->hotelRoomAdapter->findById($accommodation->roomInfo()->id());
        $expectedGuestCount = $accommodation->guestsCount() + 1;
        //@todo перенести валидацию в сервис
        if ($expectedGuestCount > $hotelRoomSettings->guestsCount) {
            throw new TooManyRoomGuestsException();
        }
    }
}
