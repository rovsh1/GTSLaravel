<?php

namespace Module\HotelOld\Domain\Repository;

use Module\HotelOld\Domain\Entity\Room;

interface RoomRepositoryInterface
{
    public function find(int $id): ?Room;

    /**
     * @param int $hotelId
     * @return Room[]
     */
    public function getRoomsWithPriceRatesByHotelId(int $hotelId): array;
}
