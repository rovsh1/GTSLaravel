<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;

use GTS\Reservation\HotelReservation\Domain\Entity\Reservation as Entity;
use GTS\Reservation\HotelReservation\Domain\Factory\ReservationFactory;
use GTS\Reservation\HotelReservation\Infrastructure\Models\Reservation as Model;

class FindHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface $query): ?Entity
    {
        $model = Model::query()->withClientType()->find($query->id);
        if (!$model) {
            return null;
        }
        return app(ReservationFactory::class)->createFrom($model);
    }
}
