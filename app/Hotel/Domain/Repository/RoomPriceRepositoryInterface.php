<?php

namespace GTS\Hotel\Domain\Repository;

interface RoomPriceRepositoryInterface
{
    public function updateRoomPrice(int $roomId, int $rateId, float $price);
}
