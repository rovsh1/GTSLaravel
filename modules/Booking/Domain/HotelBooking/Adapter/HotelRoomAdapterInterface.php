<?php

namespace Module\Booking\Domain\HotelBooking\Adapter;

use Module\Catalog\Application\Admin\Response\RoomDto;

interface HotelRoomAdapterInterface
{
    public function findById(int $id): mixed;

    /**
     * @param int $hotelId
     * @return array<int, RoomDto>
     */
    public function getByHotelId(int $hotelId): array;
}
