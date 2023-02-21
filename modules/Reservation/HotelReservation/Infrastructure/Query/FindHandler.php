<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Reservation\HotelReservation\Application\Query\Find;
use Module\Reservation\HotelReservation\Domain\Entity\Reservation as Entity;
use Module\Reservation\HotelReservation\Domain\Factory\ReservationFactory;
use Module\Reservation\HotelReservation\Infrastructure\Models\Reservation as Model;

class FindHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface|Find $query): ?Entity
    {
        $model = Model::query()->withClientType()->find($query->id);
        if (!$model) {
            return null;
        }
        return app(ReservationFactory::class)->createFrom($model);
    }
}
