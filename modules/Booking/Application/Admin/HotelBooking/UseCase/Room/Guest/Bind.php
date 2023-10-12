<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase\Room\Guest;

use Module\Booking\Application\Admin\HotelBooking\Exception\TooManyRoomGuestsException;
use Module\Booking\Deprecated\HotelBooking\Event\GuestBinded;
use Module\Booking\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\HotelBooking\BookingGuestRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Domain\Shared\ValueObject\GuestId;
use Module\Shared\Contracts\Domain\DomainEntityExceptionInterface;
use Module\Shared\Exception\ApplicationException;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Bind implements UseCaseInterface
{
    public function __construct(
        private readonly BookingGuestRepositoryInterface $bookingGuestRepository,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $bookingId, int $roomBookingId, int $guestId): void
    {
        try {
            $booking = $this->bookingRepository->find(new BookingId($bookingId));
            if ($booking === null) {
                throw new EntityNotFoundException('Booking not found');
            }
            $roomBooking = $this->roomBookingRepository->find(new RoomBookingId($roomBookingId));
            if ($roomBooking === null) {
                throw new EntityNotFoundException('Room booking not found');
            }
            $hotelRoomSettings = $this->hotelRoomAdapter->findById($roomBooking->roomInfo()->id());
            $expectedGuestCount = $roomBooking->guestIds()->count() + 1;
            //@todo перенести валидацию в сервис
            if ($expectedGuestCount > $hotelRoomSettings->guestsCount) {
                throw new TooManyRoomGuestsException();
            }

            $roomId = new RoomBookingId($roomBookingId);
            $newGuestId = new GuestId($guestId);
            $this->bookingGuestRepository->bind($roomId, $newGuestId);
            $roomBooking->addGuest($newGuestId);
            $this->roomBookingRepository->store($roomBooking);
            $this->eventDispatcher->dispatch(
                new GuestBinded(
                    $booking->id(),
                    $booking->orderId(),
                    $roomId,
                    $newGuestId,
                )
            );
        } catch (DomainEntityExceptionInterface $e) {
            throw new ApplicationException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
