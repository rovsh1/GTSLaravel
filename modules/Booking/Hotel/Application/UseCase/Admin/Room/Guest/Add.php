<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Room\Guest;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Hotel\Application\Request\Guest\AddRoomGuestDto;
use Module\Booking\Hotel\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Hotel\Domain\Exception\TooManyRoomGuests;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\Guest;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Module\Shared\Application\Exception\BaseApplicationException;
use Module\Shared\Domain\Exception\DomainEntityExceptionInterface;
use Module\Shared\Domain\ValueObject\GenderEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Add implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(AddRoomGuestDto $request): void
    {
        try {
            $booking = $this->repository->find($request->bookingId);
            $room = $booking->roomBookings()->get($request->roomIndex);
            $hotelRoomSettings = $this->hotelRoomAdapter->findById($room->roomInfo()->id());
            $expectedGuestCount = $room->guests()->count() + 1;
            //@todo перенести валидацию в сервис
            if ($expectedGuestCount > $hotelRoomSettings->guestsNumber) {
                throw new TooManyRoomGuests(
                    "Room doesn't support {$expectedGuestCount} guests, max {$hotelRoomSettings->guestsNumber} available."
                );
            }

            $booking->roomBookings()->get($request->roomIndex)->addGuest(
                new Guest(
                    $request->fullName,
                    $request->countryId,
                    GenderEnum::from($request->gender),
                    $request->isAdult,
                )
            );
            $this->bookingUpdater->store($booking);
        } catch (DomainEntityExceptionInterface $e) {
            throw new BaseApplicationException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
