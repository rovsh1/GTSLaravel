<?php

namespace Module\HotelOld\Infrastructure\Repository;

use Module\HotelOld\Domain\Entity\Hotel;
use Module\HotelOld\Domain\Factory\HotelFactory;
use Module\HotelOld\Domain\Repository\HotelRepositoryInterface;
use Module\HotelOld\Infrastructure\Models\Hotel as HotelEloquent;

class HotelRepository implements HotelRepositoryInterface
{
    public function find(int $id): ?Hotel
    {
        $hotel = HotelEloquent::find($id);

        return app(HotelFactory::class)->createFrom($hotel);
    }
}
