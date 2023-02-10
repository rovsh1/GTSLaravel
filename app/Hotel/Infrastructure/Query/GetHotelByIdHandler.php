<?php

namespace GTS\Hotel\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use GTS\Hotel\Application\Query\GetHotelById;
use GTS\Hotel\Domain\Entity\Hotel;
use GTS\Hotel\Domain\Factory\HotelFactory;
use GTS\Hotel\Infrastructure\Models\Hotel as HotelEloquent;

class GetHotelByIdHandler implements QueryHandlerInterface
{
    /**
     * @param GetHotelById $query
     * @return Hotel
     */
    public function handle(QueryInterface $query): ?Hotel
    {
        $hotel = HotelEloquent::find($query->id);

        return app(HotelFactory::class)->createFrom($hotel);
    }
}
