<?php

namespace GTS\Hotel\Infrastructure\Repository;

use Carbon\CarbonInterface;

use GTS\Hotel\Domain\Repository\RoomPriceRepositoryInterface;
use GTS\Hotel\Infrastructure\Models\Room\Price as EloquentPrice;

class RoomPriceRepository implements RoomPriceRepositoryInterface
{
    public function updateRoomPrices(int $roomId, int $seasonId, int $rateId, int $guestsNumber, int $type, CarbonInterface $date, float $price)
    {
        $roomPriceData = [
            'room_id' => $roomId,
            'season_id' => $seasonId,
            'rate_id' => $rateId,
            'guests_number' => $guestsNumber,
            'type' => $type,
            'date' => $date,
            'price' => $price,
        ];

        EloquentPrice::updateOrCreate($roomPriceData);
    }
}
