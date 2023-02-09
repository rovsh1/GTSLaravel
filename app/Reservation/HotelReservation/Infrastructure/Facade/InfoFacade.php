<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Facade;

use Custom\Framework\Contracts\Bus\QueryBusInterface;
use GTS\Reservation\HotelReservation\Application\Dto\ReservationDto;
use GTS\Reservation\HotelReservation\Application\Query\Find;

class InfoFacade implements InfoFacadeInterface
{
    public function __construct(private QueryBusInterface $queryBus) {}

    public function findById(int $id): ReservationDto
    {
        $reservation = $this->queryBus->execute(new Find($id));

        return ReservationDto::from($reservation);
    }
}
