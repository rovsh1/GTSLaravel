<?php

namespace GTS\Hotel\UI\Port\Controllers;

use Custom\Framework\Port\Request;
use GTS\Hotel\Infrastructure\Facade\ReservationFacadeInterface;

class ReservationController
{
    public function __construct(private ReservationFacadeInterface $reservationFacade) {}

    public function reserveQuota(Request $request)
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'date' => 'required|date',
            'count' => 'nullable|int',
        ]);

        return $this->reservationFacade->reserveQuota(
            $request->room_id,
            $request->date,
            $request->count ?? 1
        );
    }

}
