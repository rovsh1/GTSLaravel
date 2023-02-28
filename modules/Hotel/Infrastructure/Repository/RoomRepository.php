<?php

namespace Module\Hotel\Infrastructure\Repository;

use Module\Hotel\Domain\Factory\RoomFactory;
use Module\Hotel\Domain\Repository\RoomRepositoryInterface;
use Module\Hotel\Infrastructure\Models\Room as RoomEloquent;

class RoomRepository implements RoomRepositoryInterface
{
    public function getRoomsWithPriceRatesByHotelId(int $hotelId): array
    {
        $rooms = RoomEloquent::query()->where('hotel_id', $hotelId)
            ->withName()
            ->withPriceRates()
            ->withBeds()
            ->get()
            ->append(['display_name']);

        return app(RoomFactory::class)->createCollectionFrom($rooms);
    }
}
