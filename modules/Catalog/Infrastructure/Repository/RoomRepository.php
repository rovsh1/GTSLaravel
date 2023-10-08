<?php

namespace Module\Catalog\Infrastructure\Repository;

use Module\Catalog\Domain\Hotel\Entity\Room;
use Module\Catalog\Domain\Hotel\Factory\RoomFactory;
use Module\Catalog\Domain\Hotel\Repository\RoomRepositoryInterface;
use Module\Catalog\Infrastructure\Models\Room as RoomEloquent;

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
