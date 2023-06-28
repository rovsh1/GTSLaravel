<?php

namespace Module\Hotel\Infrastructure\Repository;

use Module\Hotel\Domain\Entity\Hotel;
use Module\Hotel\Domain\Factory\HotelFactory;
use Module\Hotel\Domain\Repository\HotelRepositoryInterface;
use Module\Hotel\Infrastructure\Models\Hotel as HotelEloquent;

class HotelRepository implements HotelRepositoryInterface
{
    public function __construct(
        private readonly HotelFactory $factory
    ) {}

    public function find(int $id): ?Hotel
    {
        $hotel = HotelEloquent::find($id);

        return $this->factory->createFrom($hotel);
    }
}
