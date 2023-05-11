<?php

namespace Module\Hotel\Port\Controllers;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Custom\Framework\PortGateway\Request;
use Illuminate\Validation\Rules\Enum;
use Module\Hotel\Application\Enums\QuotaAvailabilityEnum;
use Module\Hotel\Application\Query\GetQuotas;
use Module\Hotel\Application\Service\RoomQuotaUpdater;

class RoomQuotaController
{
    public function __construct(
        private readonly RoomQuotaUpdater $quotaUpdater,
        private readonly QueryBusInterface $queryBus
    ) {}

    public function getHotelQuotas(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|numeric',
            'room_id' => 'nullable|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'availability' => ['nullable', new Enum(QuotaAvailabilityEnum::class)]
        ]);

        return $this->queryBus->execute(
            new GetQuotas(
                $request->hotel_id,
                new CarbonPeriod($request->date_from, $request->date_to),
                $request->room_id,
                $request->availability !== null ? QuotaAvailabilityEnum::from($request->availability) : null
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
}
