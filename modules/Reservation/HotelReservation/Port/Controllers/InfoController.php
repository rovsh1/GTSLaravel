<?php

namespace Module\Reservation\HotelReservation\Port\Controllers;

use Custom\Framework\Port\Request;
use Module\Reservation\HotelReservation\Application\Dto\ReservationDto;
use Module\Reservation\HotelReservation\Infrastructure\Facade\InfoFacadeInterface;

class InfoController
{
    public function __construct(
        private InfoFacadeInterface $infoFacade
    ) {}

    public function findById(Request $request): ReservationDto
    {
        $request->validate([
            'id' => 'required|int',
        ]);
        return $this->infoFacade->findById($request->id);
    }

    public function searchActiveReservations(Request $request): array
    {
        $request->validate([
            'hotel_id' => 'nullable|int',
        ]);
        return $this->infoFacade->searchActiveReservations($request->hotel_id);
    }

    public function searchUpdatedReservations(Request $request): array
    {
        $request->validate([
            'date_update' => 'required|date',
            'hotel_id' => 'nullable|int',
        ]);
        //@todo как передавать критерии поиска?
        return $this->infoFacade->searchReservations($request->attributes());
    }

}
