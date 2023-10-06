<?php

namespace Module\Pricing\Domain\HotelBooking\Service;

use Module\Pricing\Domain\HotelBooking\Adapter\HotelAdapterInterface;
use Module\Pricing\Domain\HotelBooking\Entity\RoomBooking;
use Module\Pricing\Domain\HotelBooking\Repository\BookingRepositoryInterface;

class RoomDataHelperFactory
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly HotelAdapterInterface $hotelAdapter,
    ) {
    }

    public function fromRoomBooking(RoomBooking $roomBooking): RoomDataHelper
    {
        $hotelBooking = $this->bookingRepository->find($roomBooking->bookingId()->value());
        $hotelDto = $this->hotelAdapter->findById($hotelBooking->hotelId()->value());
        $markupDto = $this->hotelAdapter->getMarkupSettings($hotelBooking->hotelId()->value());

        return new RoomDataHelper(
            $roomBooking,
            $hotelBooking,
            $hotelDto,
            $markupDto
        );
    }
}
