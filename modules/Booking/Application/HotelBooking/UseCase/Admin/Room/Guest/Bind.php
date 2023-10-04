<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\UseCase\Admin\Room\Guest;

use Module\Booking\Application\HotelBooking\Exception\TooManyRoomGuestsException;
use Module\Booking\Domain\HotelBooking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Domain\HotelBooking\Event\GuestBinded;
use Module\Booking\Domain\HotelBooking\Repository\BookingGuestRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\Domain\Order\ValueObject\GuestId;
use Module\Shared\Application\Exception\ApplicationException;
use Module\Shared\Domain\Exception\DomainEntityExceptionInterface;
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
            $booking = $this->bookingRepository->find($bookingId);
            if ($booking === null) {
                throw new EntityNotFoundException('Booking not found');
            }
            $roomBooking = $this->roomBookingRepository->find($roomBookingId);
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