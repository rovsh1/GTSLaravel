<?php

namespace GTS\Hotel\Infrastructure\Query;

use GTS\Hotel\Application\Query\GetHotelById;
use GTS\Hotel\Domain\Entity\Hotel;
use GTS\Hotel\Domain\Factory\HotelFactory;
use GTS\Hotel\Infrastructure\Models\Hotel as HotelEloquent;
use GTS\Shared\Application\Query\QueryHandlerInterface;
use GTS\Shared\Application\Query\QueryInterface;

class GetHotelByIdHandler implements QueryHandlerInterface
{
    /**
     * @param GetHotelById $query
     * @return Hotel
     */
    public function handle(QueryInterface $query): Hotel
    {
        $hotel = HotelEloquent::find($query->id);
        //@todo что делать если не найден?

        return HotelFactory::createFrom($hotel);
    }
}
