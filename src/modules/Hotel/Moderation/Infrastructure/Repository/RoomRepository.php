<?php

namespace Module\Hotel\Moderation\Infrastructure\Repository;

use Module\Hotel\Moderation\Domain\Hotel\Entity\Room;
use Module\Hotel\Moderation\Domain\Hotel\Factory\RoomFactory;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomRepositoryInterface;
use Module\Hotel\Moderation\Infrastructure\Models\Room as RoomEloquent;

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
