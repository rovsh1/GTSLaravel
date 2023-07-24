<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support;

use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;

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

        return new RoomDataHelper(
            $roomBooking,
            $hotelBooking,
            $order,
            $hotelDto,
            $markupDto
        );
    }
}
