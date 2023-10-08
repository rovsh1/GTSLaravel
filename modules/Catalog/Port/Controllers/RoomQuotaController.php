<?php

namespace Module\Catalog\Port\Controllers;

use Carbon\CarbonPeriod;
use Module\Catalog\Application\Admin\Service\RoomQuotaUpdater;
use Module\Hotel\Application\Query\Quota;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\PortGateway\Request;

class RoomQuotaController
{
    public function __construct(
        private readonly RoomQuotaUpdater $quotaUpdater,
        private readonly QueryBusInterface $queryBus
    ) {}

    public function getQuotas(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
            'room_id' => 'nullable|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        return $this->queryBus->execute(
            new \Module\Catalog\Application\Admin\Query\Quota\Get(
                $request->hotel_id,
                new CarbonPeriod($request->date_from, $request->date_to),
                $request->room_id,
            )
        );
    }

    public function getAvailableQuotas(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
            'room_id' => 'nullable|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        return $this->queryBus->execute(
            new \Module\Catalog\Application\Admin\Query\Quota\GetAvailable(
                $request->hotel_id,
                new CarbonPeriod($request->date_from, $request->date_to),
                $request->room_id,
            )
        );
    }

    public function getSoldQuotas(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
            'room_id' => 'nullable|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        return $this->queryBus->execute(
            new \Module\Catalog\Application\Admin\Query\Quota\GetSold(
                $request->hotel_id,
                new CarbonPeriod($request->date_from, $request->date_to),
                $request->room_id,
            )
        );
    }

    public function getStoppedQuotas(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
            'room_id' => 'nullable|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        return $this->queryBus->execute(
            new \Module\Catalog\Application\Admin\Query\Quota\GetStopped(
                $request->hotel_id,
                new CarbonPeriod($request->date_from, $request->date_to),
                $request->room_id,
            )
        );
    }

    public function updateRoomQuota(Request $request): void
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'quota' => ['required_without:release_days', 'nullable', 'integer'],
            'release_days' => ['required_without:quota', 'nullable', 'integer'],
        ]);

        $this->quotaUpdater->updateRoomQuota(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
            $request->quota,
            $request->release_days
        );
    }

    public function openRoomQuota(Request $request): void
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        $this->quotaUpdater->openRoomQuota(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
        );
    }

    public function closeRoomQuota(Request $request): void
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        $this->quotaUpdater->closeRoomQuota(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
        );
    }

    public function resetRoomQuota(Request $request): void
    {
        $request->validate([
            'room_id' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        $this->quotaUpdater->resetRoomQuota(
            $request->room_id,
            new CarbonPeriod($request->date_from, $request->date_to),
        );
    }

    private function validateGetQuotaRequest(Request $request): void {}
}