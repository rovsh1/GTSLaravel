<?php

namespace GTS\Hotel\Infrastructure\Repository;


use GTS\Hotel\Domain\Model\Hotel;
use GTS\Hotel\Domain\Repository\HotelRepositoryInterface;

class HotelRepository implements HotelRepositoryInterface
{

    public function findById(int $id): Hotel
    {
        $record = HotelEloquent::find();
        //TODO convert to Domain Model (new Hotel)
        return $record;
    }
}
