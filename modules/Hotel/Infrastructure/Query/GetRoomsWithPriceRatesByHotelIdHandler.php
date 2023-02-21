<?php

namespace Module\Hotel\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Hotel\Application\Query\GetRoomsWithPriceRatesByHotelId;
use Module\Hotel\Domain\Entity\Room;
use Module\Hotel\Domain\Factory\RoomFactory;
use Module\Hotel\Infrastructure\Models\Room as RoomEloquent;

class GetRoomsWithPriceRatesByHotelIdHandler implements QueryHandlerInterface
{
    /**
     * @param GetRoomsWithPriceRatesByHotelId $query
     * @return Room[]
     */
    public function handle(QueryInterface|GetRoomsWithPriceRatesByHotelId $query): array
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
