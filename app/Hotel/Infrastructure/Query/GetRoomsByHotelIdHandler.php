<?php

namespace GTS\Hotel\Infrastructure\Query;

use GTS\Hotel\Application\Query\GetRoomsByHotelId;
use GTS\Hotel\Domain\Entity\Room;
use GTS\Hotel\Domain\Factory\EntityFactory;
use GTS\Hotel\Infrastructure\Models\Room as RoomEloquent;
use GTS\Shared\Application\Query\QueryHandlerInterface;
use GTS\Shared\Application\Query\QueryInterface;

class GetRoomsByHotelIdHandler implements QueryHandlerInterface
{
    /**
     * @param GetRoomsByHotelId $query
     * @return Room[]
     */
    public function handle(QueryInterface $query): array
    {
        $rooms = RoomEloquent::query()->where('hotel_id', $query->hotelId)->get();

        return EntityFactory::createCollection(Room::class, $rooms);
    }
}
