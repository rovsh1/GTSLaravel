<?php

namespace GTS\Hotel\UI\Port\Controllers;

use Carbon\CarbonPeriod;
use Custom\Framework\Port\Request;
use GTS\Hotel\Infrastructure\Facade\ReservationFacadeInterface;

class ReservationController
{
    public function __construct(private ReservationFacadeInterface $reservationFacade) {}

    public function updateRoomQuota(Request $request)
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'quota' => 'required|int',
        ]);

        return $this->reservationFacade->updateRoomQuota(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
            $request->quota
        );
    }

    public function updateRoomPrice(Request $request)
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'rate_id' => 'required|numeric',
            'price' => 'required|numeric',
            'currency_code' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        return $this->reservationFacade->updateRoomPrice(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
            $request->rate_id,
            $request->price,
            $request->currency_code,
        );
    }

    public function openRoomQuota(Request $request)
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'rate_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        return $this->reservationFacade->openRoomQuota(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
            $request->rate_id,
        );
    }

    public function closeRoomQuota(Request $request)
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'rate_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        return $this->reservationFacade->closeRoomQuota(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
            $request->rate_id,
        );
    }
}
