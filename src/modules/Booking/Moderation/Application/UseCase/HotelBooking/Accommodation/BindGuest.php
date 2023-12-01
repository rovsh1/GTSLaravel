<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation;

use Module\Booking\Moderation\Application\Exception\TooManyRoomGuestsException;
use Module\Booking\Moderation\Application\Service\GuestValidator;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class BindGuest implements UseCaseInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly GuestValidator $guestValidator,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter
    ) {}

    public function execute(int $bookingId, int $accommodationId, int $guestId): void
    {
        $accommodation = $this->accommodationRepository->findOrFail(new AccommodationId($accommodationId));

        $this->guestValidator->ensureCanBindToBooking($bookingId, $guestId);
        $this->ensureGuestsCountAllowed($accommodation);

        $this->bookingUnitOfWork->persist($accommodation);
        $accommodation->addGuest(new GuestId($guestId));
        $this->bookingUnitOfWork->commit();
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
