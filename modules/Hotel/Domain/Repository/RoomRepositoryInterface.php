<?php

namespace Module\Hotel\Domain\Repository;

use Module\Hotel\Domain\Entity\Room;

interface RoomRepositoryInterface
{
    public function find(int $id): ?Room;

    /**
     * @param int $hotelId
     * @return Room[]
     */
    public function getRoomsWithPriceRatesByHotelId(int $hotelId): array;
}
