<?php

namespace GTS\Hotel\Infrastructure\Repository;

use GTS\Hotel\Domain\Repository\RoomPriceRepositoryInterface;
use GTS\Hotel\Infrastructure\Models\Room\Price as EloquentPrice;

class RoomPriceRepository implements RoomPriceRepositoryInterface
{
    public function updateRoomPrice(int $roomId, int $rateId, float $price)
    {
        EloquentPrice::whereRoomId($roomId)
            ->whereRateId($rateId)
            ->update(['price' => $price]);
    }
}
