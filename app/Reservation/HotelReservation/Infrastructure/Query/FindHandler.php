<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Query;

use GTS\Reservation\HotelReservation\Domain\Entity\Reservation as Entity;
use GTS\Reservation\HotelReservation\Infrastructure\Models\Reservation as Model;
use GTS\Shared\Application\Query\QueryHandlerInterface;
use GTS\Shared\Application\Query\QueryInterface;

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
