<?php

namespace GTS\Hotel\UI\Port\Controllers;

use Carbon\CarbonPeriod;
use Custom\Framework\Port\Request;
use GTS\Hotel\Infrastructure\Facade\ReservationFacadeInterface;

class ReservationController
{
    public function __construct(private ReservationFacadeInterface $reservationFacade) {}

    public function reserveQuota(Request $request)
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'count' => 'nullable|int',
        ]);

        return $this->reservationFacade->reserveQuota(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
            $request->count ?? 1
        );
    }

}
