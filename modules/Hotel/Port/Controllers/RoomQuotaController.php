<?php

namespace Module\Hotel\Port\Controllers;

use Carbon\CarbonPeriod;
use Custom\Framework\Port\Request;
use Module\Hotel\Infrastructure\Facade\RoomQuotaFacadeInterface;

class RoomQuotaController
{
    public function __construct(
        private RoomQuotaFacadeInterface $roomQuotaFacade,
    ) {}

    public function updateRoomQuota(Request $request)
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'quota' => 'required|int',
        ]);

        return $this->roomQuotaFacade->updateRoomQuota(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
            $request->quota
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

        return $this->roomQuotaFacade->openRoomQuota(
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

        return $this->roomQuotaFacade->closeRoomQuota(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
            $request->rate_id,
        );
    }
}
