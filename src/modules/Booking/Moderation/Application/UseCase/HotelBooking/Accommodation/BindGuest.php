<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Accommodation;

use Module\Booking\Moderation\Application\Exception\NotFoundHotelRoomPriceException;
use Module\Booking\Moderation\Application\Exception\TooManyRoomGuestsException;
use Module\Booking\Moderation\Domain\Booking\Event\HotelBooking\GuestBinded;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\HotelBooking\BookingGuestRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationId;
use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;
use Module\Hotel\Pricing\Domain\Hotel\Exception\NotFoundHotelRoomPrice;
use Module\Shared\Contracts\Service\SafeExecutorInterface;
use Module\Shared\Exception\ApplicationException;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class BindGuest implements UseCaseInterface
{
    public function __construct(
        private readonly BookingGuestRepositoryInterface $bookingGuestRepository,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly SafeExecutorInterface $executor,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $bookingId, int $accommodationId, int $guestId): void
    {
        try {
            $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));
            $accommodation = $this->accommodationRepository->findOrFail(new AccommodationId($accommodationId));
            $hotelRoomSettings = $this->hotelRoomAdapter->findById($accommodation->roomInfo()->id());
            $expectedGuestCount = $accommodation->guestsCount() + 1;
            //@todo перенести валидацию в сервис
            if ($expectedGuestCount > $hotelRoomSettings->guestsCount) {
                throw new TooManyRoomGuestsException();
            }

            $this->executor->execute(function () use ($booking, $accommodation, $guestId) {
                $newGuestId = new GuestId($guestId);
                $this->bookingGuestRepository->bind($accommodation->id(), $newGuestId);
                $accommodation->addGuest($newGuestId);
                $this->accommodationRepository->store($accommodation);
                $this->eventDispatcher->dispatch(
                    new GuestBinded($booking->id(), $booking->orderId(), $accommodation->id(), $newGuestId)
                );
            });
        } catch (NotFoundHotelRoomPrice $e) {
            throw new NotFoundHotelRoomPriceException($e);
        } catch (\Throwable $e) {
            throw new ApplicationException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
