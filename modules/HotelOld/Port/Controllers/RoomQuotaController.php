<?php

namespace Module\HotelOld\Port\Controllers;

use Carbon\CarbonPeriod;
use Module\HotelOld\Application\Service\RoomQuotaUpdater;
use Sdk\Module\PortGateway\Request;

class RoomQuotaController
{
    public function __construct(
        private readonly RoomQuotaUpdater $quotaUpdater,
    ) {}

    public function updateRoomQuota(Request $request)
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'quota' => 'required|int',
        ]);

        $this->quotaUpdater->updateRoomQuota(
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

        $this->quotaUpdater->openRoomQuota(
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

        $this->quotaUpdater->closeRoomQuota(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
            $request->rate_id,
        );
    }
}
