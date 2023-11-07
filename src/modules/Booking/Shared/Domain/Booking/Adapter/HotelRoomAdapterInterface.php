<?php

namespace Module\Booking\Shared\Domain\Booking\Adapter;

use Module\Catalog\Application\Admin\Response\RoomDto;

interface HotelRoomAdapterInterface
{
    public function findById(int $id): ?RoomDto;

    /**
     * @param int $hotelId
     * @return array<int, RoomDto>
     */
    public function getByHotelId(int $hotelId): array;
}
