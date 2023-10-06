<?php

namespace Module\Booking\Domain\HotelBooking\Service\PriceCalculator;

use Module\Booking\Domain\HotelBooking\Adapter\HotelAdapterInterface;
use Module\Booking\Domain\HotelBooking\Entity\RoomBooking;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Order\Repository\OrderRepositoryInterface;

class RoomDataHelperFactory
{
    public function __construct(
        private readonly RoomBookingRepositoryInterface $roomRepository,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly HotelAdapterInterface $hotelAdapter,
    ) {
    }

    public function fromRoomBookingId(int $id): RoomDataHelper
    {
        return $this->fromRoomBooking($this->roomRepository->find($id));
    }

    public function fromRoomBooking(RoomBooking $roomBooking): RoomDataHelper
    {
        $hotelBooking = $this->bookingRepository->find($roomBooking->bookingId()->value());
        $order = $this->orderRepository->find($hotelBooking->orderId()->value());
        $hotelDto = $this->hotelAdapter->findById($hotelBooking->hotelInfo()->id());
        $markupDto = $this->hotelAdapter->getMarkupSettings($hotelBooking->hotelInfo()->id());
        $roomMarkupDto = $this->hotelAdapter->getRoomMarkupSettings($roomBooking->roomInfo()->id());

        return new RoomDataHelper(
            $roomBooking,
            $hotelBooking,
            $order,
            $hotelDto,
            $markupDto,
            $roomMarkupDto
        );
    }
}
