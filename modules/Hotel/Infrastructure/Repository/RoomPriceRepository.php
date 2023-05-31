<?php

namespace Module\Hotel\Infrastructure\Repository;

use Carbon\CarbonInterface;
use Module\Hotel\Domain\Repository\RoomPriceRepositoryInterface;
use Module\Hotel\Infrastructure\Models\Room\Price as EloquentPrice;
use Module\Hotel\Infrastructure\Models\Room\PriceTypeEnum;

class RoomPriceRepository implements RoomPriceRepositoryInterface
{
    public function updateRoomPrices(
        int $roomId,
        int $seasonId,
        int $rateId,
        int $guestsNumber,
        bool $isResident,
        CarbonInterface $date,
        float $price
    ) {
        $roomPriceData = [
            'room_id' => $roomId,
            'season_id' => $seasonId,
            'rate_id' => $rateId,
            'guests_number' => $guestsNumber,
            'type' => $isResident ? PriceTypeEnum::Resident : PriceTypeEnum::Nonresident,
            'date' => $date->toDateString(),
            'price' => $price
        ];

        try {
            EloquentPrice::upsert([$roomPriceData], array_keys($roomPriceData), ['price']);
        } catch (\Throwable $e) {
            dd($e);
        }
    }
}
