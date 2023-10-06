<?php

declare(strict_types=1);

namespace Module\Booking\Application\HotelBooking\Factory;

use Module\Booking\Application\HotelBooking\Request\AddRoomDto;
use Module\Booking\Application\HotelBooking\Request\UpdateRoomDto;
use Module\Booking\Domain\HotelBooking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Service\RoomUpdater\UpdateDataHelper;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\Condition;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPrice;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\TimePeriod;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class RoomUpdaterDataHelperFactory
{
    public function __construct(
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly BookingRepositoryInterface $bookingRepository,
    ) {}

    public function build(
        AddRoomDto|UpdateRoomDto $request,
        GuestIdCollection $guestIds,
        RoomPrice $price
    ): UpdateDataHelper {
        $booking = $this->bookingRepository->find($request->bookingId);
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }

        $hotelRoomDto = $this->hotelRoomAdapter->findById($request->roomId);
        if ($hotelRoomDto === null) {
            throw new EntityNotFoundException('Hotel room not found');
        }

        $earlyCheckIn = $request->earlyCheckIn !== null ? $this->buildMarkupCondition($request->earlyCheckIn) : null;
        $lateCheckOut = $request->lateCheckOut !== null ? $this->buildMarkupCondition($request->lateCheckOut) : null;

        return new UpdateDataHelper(
            booking: $booking,
            roomInfo: new RoomInfo(
                $hotelRoomDto->id,
                $hotelRoomDto->name,
            ),
            guestIds: $guestIds,
            details: new RoomBookingDetails(
                rateId: $request->rateId,
                isResident: $request->isResident,
                earlyCheckIn: $earlyCheckIn,
                lateCheckOut: $lateCheckOut,
                guestNote: $request->note,
                discount: new Percent($request->discount ?? 0),
            ),
            price: $price
        );
    }

    private function buildMarkupCondition(array $data): Condition
    {
        return new Condition(
            new TimePeriod($data['from'], $data['to']),
            new Percent($data['percent'])
        );
    }
}
