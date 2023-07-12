<?php

namespace Module\Booking\PriceCalculator\Domain\Service\Hotel\Support;

use Module\Booking\Hotel\Domain\Entity\RoomBooking;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Hotel\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Module\Booking\PriceCalculator\Domain\Adapter\HotelAdapterInterface;

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
