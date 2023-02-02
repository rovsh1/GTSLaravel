<?php

namespace GTS\Hotel\Infrastructure\Query;

use GTS\Hotel\Application\Query\GetRoomsWithPriceRatesByHotelId;
use GTS\Hotel\Domain\Entity\Room;
use GTS\Hotel\Domain\Factory\RoomFactory;
use GTS\Hotel\Infrastructure\Models\Room as RoomEloquent;
use GTS\Shared\Application\Query\QueryHandlerInterface;
use GTS\Shared\Application\Query\QueryInterface;

class GetRoomsWithPriceRatesByHotelIdHandler implements QueryHandlerInterface
{
    /**
     * @param GetRoomsWithPriceRatesByHotelId $query
     * @return Room[]
     */
    public function handle(QueryInterface $query): array
    {
        $rooms = RoomEloquent::query()->where('hotel_id', $query->hotelId)
            ->withPriceRates()
            ->get();

        return RoomFactory::createCollectionFrom($rooms);
    }
}
