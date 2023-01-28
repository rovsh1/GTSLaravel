<?php

namespace GTS\Hotel\Infrastructure\Repository;


use GTS\Hotel\Domain\Entity\Hotel;
use GTS\Hotel\Domain\Repository\HotelRepositoryInterface;
use GTS\Hotel\Infrastructure\Models\Hotel as HotelEloquent;
use GTS\Shared\Domain\Exception\EntityNotFoundException;

class HotelRepository implements HotelRepositoryInterface
{
    public function findById(int $id): Hotel
    {
        $record = HotelEloquent::find($id);
        if ($record === null) {
            throw new EntityNotFoundException();
        }
        return new Hotel($record->id);
    }
}
