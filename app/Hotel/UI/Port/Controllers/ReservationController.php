<?php

namespace GTS\Hotel\UI\Port\Controllers;

use Custom\Framework\Port\Request;
use GTS\Hotel\Infrastructure\Facade\ReservationFacadeInterface;

class ReservationController
{
    public function __construct(
        private ReservationFacadeInterface $reservationFacade
    ) {}

    public function getActiveReservations(Request $request): array
    {
        $request->validate([
            'hotel_id' => 'required|numeric'
        ]);
        return $this->reservationFacade->getActiveReservations($request->hotel_id);
    }
}
