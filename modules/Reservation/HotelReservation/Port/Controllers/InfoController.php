<?php

namespace Module\Reservation\HotelReservation\Port\Controllers;

use Custom\Framework\Port\Request;
use Module\Reservation\HotelReservation\Application\Dto\ReservationDto;
use Module\Reservation\HotelReservation\Application\Service\InfoService;

class InfoController
{
    public function __construct(
        private InfoService $infoService
    ) {}

    public function findById(Request $request): ?ReservationDto
    {
        $request->validate([
            'id' => 'required|int',
        ]);
        return $this->infoService->findById($request->id);
    }

    public function searchActiveReservations(Request $request): array
    {
        $request->validate([
            'hotel_id' => 'nullable|int',
        ]);
        return $this->infoService->searchActiveReservations($request->hotel_id);
    }

    public function searchUpdatedReservations(Request $request): array
    {
        $request->validate([
            'date_update' => 'required|date',
            'hotel_id' => 'nullable|int',
        ]);
        return $this->infoService->searchByUpdatedDate($request->date_update, $request->hotel_id);
    }

}
