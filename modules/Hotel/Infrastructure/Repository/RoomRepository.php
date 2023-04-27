<?php

namespace Module\Hotel\Infrastructure\Repository;

use Module\Hotel\Domain\Entity\Room;
use Module\Hotel\Domain\Factory\RoomFactory;
use Module\Hotel\Domain\Repository\RoomRepositoryInterface;
use Module\Hotel\Infrastructure\Models\Room as RoomEloquent;

class RoomRepository implements RoomRepositoryInterface
{
    public function find(int $id): ?Room
    {
        $room = RoomEloquent::query()
            ->withPriceRates()
            ->withBeds()
            ->find($id);
        if ($room === null) {
            return null;
        }

        return app(RoomFactory::class)->createFrom($room);
    }

    public function getRoomsWithPriceRatesByHotelId(int $hotelId): array
    {
        $rooms = RoomEloquent::query()->where('hotel_id', $hotelId)
            ->withPriceRates()
            ->withBeds()
            ->get();

        return app(RoomFactory::class)->createCollectionFrom($rooms);
    }
}
