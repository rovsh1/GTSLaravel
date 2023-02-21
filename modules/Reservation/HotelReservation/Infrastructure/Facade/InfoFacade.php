<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Facade;

use Carbon\CarbonInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Module\Reservation\HotelReservation\Application\Dto\ReservationDto;
use Module\Reservation\HotelReservation\Application\Query\Find;
use Module\Reservation\HotelReservation\Application\Query\GetActive;
use Module\Reservation\HotelReservation\Application\Query\SearchByDateUpdate;

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

    public function searchReservationsByDateUpdate(CarbonInterface $dateUpdate, ?int $hotelId): array
    {
        $reservations = $this->queryBus->execute(new SearchByDateUpdate($dateUpdate, $hotelId));

        return ReservationDto::collectionFromEntity($reservations);
    }
}
