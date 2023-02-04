<?php

namespace GTS\Reservation\Infrastructure\Query\HotelReservation;

use GTS\Reservation\Domain\Entity\HotelReservation as Entity;
use GTS\Reservation\Domain\Factory\HotelReservationFactory;
use GTS\Reservation\Infrastructure\Models\HotelReservation as Model;
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
