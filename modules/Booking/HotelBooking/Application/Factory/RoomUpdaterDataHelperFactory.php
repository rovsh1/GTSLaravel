<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Factory;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Application\Request\AddRoomDto;
use Module\Booking\HotelBooking\Application\Request\UpdateRoomDto;
use Module\Booking\HotelBooking\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\UpdateDataHelper;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\Condition;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomPrice;
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
        GuestCollection $guests,
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
            status: RoomBookingStatusEnum::from($request->status),
            roomInfo: new RoomInfo(
                $hotelRoomDto->id,
                $hotelRoomDto->name,
            ),
            guests: $guests,
            details: new RoomBookingDetails(
                rateId: $request->rateId,
                isResident: $request->isResident,
                guestNote: $request->note,
                earlyCheckIn: $earlyCheckIn,
                lateCheckOut: $lateCheckOut,
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
