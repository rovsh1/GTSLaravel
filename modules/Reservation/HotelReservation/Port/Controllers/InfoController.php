<?php

namespace Module\Reservation\HotelReservation\Port\Controllers;

use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Custom\Framework\Port\Request;
use Module\Reservation\HotelReservation\Application\Dto\ReservationDto;
use Module\Reservation\HotelReservation\Application\Query\Find;
use Module\Reservation\HotelReservation\Application\Query\GetActive;
use Module\Reservation\HotelReservation\Application\Query\SearchByDateUpdate;

class InfoController
{
    public function __construct(
        private QueryBusInterface $queryBus
    ) {}

    public function findById(Request $request): ReservationDto
    {
        $request->validate([
            'id' => 'required|int',
        ]);
        return $this->queryBus->execute(new Find($request->id));
    }

    public function searchActiveReservations(Request $request): array
    {
        $request->validate([
            'hotel_id' => 'nullable|int',
        ]);
        return $this->queryBus->execute(new GetActive($request->hotel_id));
    }

    public function searchUpdatedReservations(Request $request): array
    {
        $request->validate([
            'date_update' => 'required|date',
            'hotel_id' => 'nullable|int',
        ]);
        return $this->queryBus->execute(new SearchByDateUpdate($request->date_update, $request->hotel_id));
    }

}
