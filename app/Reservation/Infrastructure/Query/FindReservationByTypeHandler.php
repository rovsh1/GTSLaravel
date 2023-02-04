<?php

namespace GTS\Reservation\Infrastructure\Query;

use GTS\Reservation\Application\Query as Queries;
use GTS\Reservation\Domain\Entity\ReservationInterface;
use GTS\Reservation\Domain\ValueObject\ReservationTypeEnum;
use GTS\Shared\Application\Query\QueryHandlerInterface;
use GTS\Shared\Application\Query\QueryInterface;

class FindReservationByTypeHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface $query): ReservationInterface
    {
        return match ($query->type) {
            ReservationTypeEnum::GROUP => $this->queryBus->execute(new Queries\HotelReservation\Find($query->id)),
            ReservationTypeEnum::HOTEL => $this->queryBus->execute(new Queries\HotelReservation\Find($query->id)),
            ReservationTypeEnum::TRANSFER => $this->queryBus->execute(new Queries\HotelReservation\Find($query->id)),
            ReservationTypeEnum::AIRPORT => $this->queryBus->execute(new Queries\HotelReservation\Find($query->id)),
            ReservationTypeEnum::ADDITIONAL => $this->queryBus->execute(new Queries\HotelReservation\Find($query->id)),
            default => throw new \Exception('Invalid reservation type'),
        };
    }
}
