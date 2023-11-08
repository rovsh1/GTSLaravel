<?php

namespace Module\Booking\Shared\Domain\Booking\Adapter;

use Module\Hotel\Moderation\Application\Response\RoomDto;

interface HotelRoomAdapterInterface
{
    public function findById(int $id): ?RoomDto;

    /**
     * @param int $hotelId
     * @return array<int, RoomDto>
     */
    public function getByHotelId(int $hotelId): array;
}
