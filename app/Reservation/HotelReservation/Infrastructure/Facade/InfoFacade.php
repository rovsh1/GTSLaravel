<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Facade;

use Custom\Framework\Contracts\Bus\QueryBusInterface;

use GTS\Reservation\HotelReservation\Application\Dto\ReservationDto;
use GTS\Reservation\HotelReservation\Application\Query\Find;
use GTS\Reservation\HotelReservation\Application\Query\GetActive;

class InfoFacade implements InfoFacadeInterface
{
    public function __construct(private QueryBusInterface $queryBus) {}

    public function findById(int $id): ReservationDto
    {
        $reservation = $this->queryBus->execute(new Find($id));

        return ReservationDto::fromEntity($reservation);
    }

    public function searchActiveReservations(?int $hotelId = null): array
    {
        $reservations = $this->queryBus->execute(new GetActive($hotelId));

        return ReservationDto::collectionFromEntity($reservations);
    }

    public function searchReservations(array $criteria): array
    {
        $reservations = [];

        // TODO: Implement searchReservations() method.
        return ReservationDto::collectionFromEntity($reservations);
    }
}
