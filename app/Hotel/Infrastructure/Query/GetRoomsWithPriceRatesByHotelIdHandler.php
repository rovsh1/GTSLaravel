<?php

namespace GTS\Hotel\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use GTS\Hotel\Application\Query\GetRoomsWithPriceRatesByHotelId;
use GTS\Hotel\Domain\Entity\Room;
use GTS\Hotel\Domain\Factory\RoomFactory;
use GTS\Hotel\Infrastructure\Models\Room as RoomEloquent;

class GetRoomsWithPriceRatesByHotelIdHandler implements QueryHandlerInterface
{
    /**
     * @param GetRoomsWithPriceRatesByHotelId $query
     * @return Room[]
     */
    public function handle(QueryInterface $query): array
    {
        $rooms = RoomEloquent::query()->where('hotel_id', $query->hotelId)
            ->withName()
            ->withPriceRates()
            ->withBeds()
            ->get()
            ->append(['display_name']);

        return app(RoomFactory::class)->createCollectionFrom($rooms);
    }
}
