<?php

namespace Module\Hotel\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Hotel\Application\Query\GetHotelById;
use Module\Hotel\Domain\Entity\Hotel;
use Module\Hotel\Domain\Factory\HotelFactory;
use Module\Hotel\Infrastructure\Models\Hotel as HotelEloquent;

class GetHotelByIdHandler implements QueryHandlerInterface
{
    /**
     * @param GetHotelById $query
     * @return Hotel
     */
    public function handle(QueryInterface|GetHotelById $query): ?Hotel
    {
        $hotel = HotelEloquent::find($query->id);

        return app(HotelFactory::class)->createFrom($hotel);
    }
}
