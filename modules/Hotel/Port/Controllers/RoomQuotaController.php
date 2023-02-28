<?php

namespace Module\Hotel\Port\Controllers;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Port\Request;
use Module\Hotel\Application\Command\CloseRoomQuota;
use Module\Hotel\Application\Command\OpenRoomQuota;
use Module\Hotel\Application\Command\UpdateRoomQuota;

class RoomQuotaController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function updateRoomQuota(Request $request)
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'quota' => 'required|int',
        ]);

        return $this->commandBus->execute(new UpdateRoomQuota(
                $request->room_id,
                new CarbonPeriod($request->date_from, $request->date_to),
                $request->quota
            )
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

        return $this->commandBus->execute(new OpenRoomQuota(
                $request->room_id,
                new CarbonPeriod($request->date_from, $request->date_to),
                $request->rate_id,
            )
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

        return $this->commandBus->execute(new CloseRoomQuota(
                $request->room_id,
                new CarbonPeriod($request->date_from, $request->date_to),
                $request->rate_id,
            )
        );
    }
}
