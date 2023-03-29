<?php

namespace Module\HotelOld\Infrastructure\Repository;

use Illuminate\Database\Query\JoinClause;
use Module\HotelOld\Domain\Repository\PriceRateRepositoryInterface;
use Module\HotelOld\Infrastructure\Models\PriceRate as Model;

class PriceRateRepository implements PriceRateRepositoryInterface
{
    public function existsByRoomAndRate(int $roomId, int $rateId): bool
    {
        $priceRate = Model::query()
            ->where('hotel_price_rates.id', $rateId)
            ->addSelect("hotel_price_rates.*")
            ->leftJoin('hotel_rooms', function (JoinClause $join) use ($roomId) {
                $join->on('hotel_rooms.hotel_id', '=', 'hotel_price_rates.hotel_id')
                    ->where('hotel_rooms.id', '=', $roomId);
            })
            ->addSelect('hotel_rooms.id as room_id')
            ->first();

        return $priceRate !== null && $priceRate->room_id !== null;
    }
}
