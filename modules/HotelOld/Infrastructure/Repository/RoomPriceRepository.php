<?php

namespace Module\HotelOld\Infrastructure\Repository;

use Carbon\CarbonInterface;
use Module\HotelOld\Domain\Repository\RoomPriceRepositoryInterface;
use Module\HotelOld\Infrastructure\Models\Room\Price as EloquentPrice;
use Module\HotelOld\Infrastructure\Models\Room\PriceTypeEnum;

class RoomPriceRepository implements RoomPriceRepositoryInterface
{
    public function updateRoomPrices(int $roomId, int $seasonId, int $rateId, int $guestsNumber, bool $isResident, CarbonInterface $date, float $price)
    {
        $roomPriceData = [
            'room_id' => $roomId,
            'season_id' => $seasonId,
            'rate_id' => $rateId,
            'guests_number' => $guestsNumber,
            'type' => $isResident ? PriceTypeEnum::Resident : PriceTypeEnum::Nonresident,
            'date' => $date,
            'price' => $price,
        ];

        EloquentPrice::updateOrCreate($roomPriceData);
    }
}
