<?php

namespace Module\Catalog\Domain\Hotel\Repository;

use Module\Catalog\Domain\Hotel\Entity\Room;

interface RoomRepositoryInterface
{
    public function find(int $id): ?Room;

    /**
     * @param int $hotelId
     * @return Room[]
     */
    public function getRoomsWithPriceRatesByHotelId(int $hotelId): array;
}
