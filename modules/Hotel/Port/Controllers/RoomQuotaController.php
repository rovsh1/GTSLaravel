<?php

namespace Module\Hotel\Port\Controllers;

use Carbon\CarbonPeriod;
use Custom\Framework\Port\Request;
use Module\Hotel\Application\Service\RoomQuotaUpdater;

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
            'release_days' => ['nullable', 'int'],
        ]);

        $this->quotaUpdater->updateRoomQuota(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
            $request->quota,
            $request->release_days,
        );
    }

    public function updateReleaseDays(Request $request)
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'release_days' => ['required', 'int'],
        ]);

        $this->quotaUpdater->updateRoomReleaseDays(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
            $request->release_days,
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
