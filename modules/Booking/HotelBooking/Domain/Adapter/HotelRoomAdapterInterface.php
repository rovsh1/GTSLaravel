<?php

namespace Module\Booking\HotelBooking\Domain\Adapter;

use Module\Hotel\Application\Response\RoomDto;

interface HotelRoomAdapterInterface
{
    public function findById(int $id): mixed;

    /**
     * @param int $hotelId
     * @return array<int, RoomDto>
     */
    public function getByHotelId(int $hotelId): array;
}
