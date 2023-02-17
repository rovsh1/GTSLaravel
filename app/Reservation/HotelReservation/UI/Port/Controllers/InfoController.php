<?php

namespace GTS\Reservation\HotelReservation\UI\Port\Controllers;

use Custom\Framework\Port\Request;

use GTS\Reservation\HotelReservation\Application\Dto\ReservationDto;
use GTS\Reservation\HotelReservation\Infrastructure\Facade\InfoFacadeInterface;

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

    public function getByHotelId(Request $request): array
    {
        $request->validate([
            'hotel_id' => 'required|int',
        ]);
        //@todo получение бронирований по id отеля
//        return $this->infoFacade->findById($request->id);
    }

}
