<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Query;

use Custom\Framework\Bus\QueryHandlerInterface;
use Custom\Framework\Bus\QueryInterface;
use GTS\Reservation\HotelReservation\Domain\Entity\Reservation as Entity;
use GTS\Reservation\HotelReservation\Infrastructure\Models\Reservation as Model;

class FindHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface $query): ?Entity
    {
        $model = Model::find($query->id);
        if (!$model)
            return null;
        //return HotelReservationFactory::fromArray($model->toArray());
    }
}
