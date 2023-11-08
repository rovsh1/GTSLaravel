<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Repository;

use Module\Hotel\Moderation\Domain\Hotel\Entity\Room;

interface RoomRepositoryInterface
{
    public function find(int $id): ?Room;

    /**
     * @param int $hotelId
     * @return Room[]
     */
    public function getRoomsWithPriceRatesByHotelId(int $hotelId): array;
}
