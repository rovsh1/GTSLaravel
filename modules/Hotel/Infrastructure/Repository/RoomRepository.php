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
            ->withName()
            ->withPriceRates()
            ->withBeds()
            ->find($id)
            ?->append(['display_name']);

        if ($room === null) {
            return null;
        }

        return app(RoomFactory::class)->createFrom($room);
    }

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
