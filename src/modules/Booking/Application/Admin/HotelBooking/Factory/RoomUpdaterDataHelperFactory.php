<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Factory;

use Module\Booking\Application\Admin\HotelBooking\Request\AddRoomDto;
use Module\Booking\Application\Admin\HotelBooking\Request\UpdateRoomDto;
use Module\Booking\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Domain\Booking\Service\HotelBooking\RoomUpdater\UpdateDataHelper;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\Condition;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingDetails;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomInfo;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\ValueObject\Percent;
use Module\Shared\ValueObject\TimePeriod;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class RoomUpdaterDataHelperFactory
{
    public function __construct(
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly HotelBookingRepositoryInterface $detailsRepository,
    ) {}

    public function build(
        AddRoomDto|UpdateRoomDto $request,
        GuestIdCollection $guestIds,
        RoomPrices $price
    ): UpdateDataHelper {
        $booking = $this->bookingRepository->find(new BookingId($request->bookingId));
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $bookingDetails = $this->detailsRepository->find($booking->id());

        $hotelRoomDto = $this->hotelRoomAdapter->findById($request->roomId);
        if ($hotelRoomDto === null) {
            throw new EntityNotFoundException('Hotel room not found');
        }

        $earlyCheckIn = $request->earlyCheckIn !== null ? $this->buildMarkupCondition($request->earlyCheckIn) : null;
        $lateCheckOut = $request->lateCheckOut !== null ? $this->buildMarkupCondition($request->lateCheckOut) : null;

        return new UpdateDataHelper(
            booking: $booking,
            bookingDetails: $bookingDetails,
            roomInfo: new RoomInfo(
                $hotelRoomDto->id,
                $hotelRoomDto->name,
                $hotelRoomDto->guestsCount,
            ),
            guestIds: $guestIds,
            roomDetails: new RoomBookingDetails(
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
