<?php

namespace Module\Hotel\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;
use Module\Hotel\Infrastructure\Models\Room\Quota as EloquentQuota;
use Module\Hotel\Infrastructure\Models\Room\QuotaTypeEnum;

class RoomQuotaRepository implements RoomQuotaRepositoryInterface
{
    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota): void
    {
        $data = $this->prepareQuotaDataByPeriod($roomId, $period, quota: $quota);
        EloquentQuota::upsert(
            $data,
            ['room_id', 'date'],
            ['count_available', 'type']
        );

        //@hack потому что сейчас система считает доступные квоты как count_available - count_booked
        $quotasWithBooked = EloquentQuota::whereRoomId($roomId)
            ->whereBetween('date', [$period->getStartDate(), $period->getEndDate()])
            ->where('count_booked', '>', 0)
            ->get();
        foreach ($quotasWithBooked as $quotaWithBooked) {
            EloquentQuota::whereRoomId($roomId)
                ->whereDate('date', $quotaWithBooked->date)
                ->update([
                    'count_available' => $quotaWithBooked->count_available + $quotaWithBooked->count_booked,
                ]);
        }
    }

    public function updateRoomReleaseDays(int $roomId, CarbonPeriod $period, int $releaseDays): void
    {
        $data = $this->prepareQuotaDataByPeriod($roomId, $period, releaseDays: $releaseDays);
        EloquentQuota::upsert(
            $data,
            ['room_id', 'date'],
            ['period']
        );
    }

    public function closeRoomQuota(int $roomId, CarbonPeriod $period)
    {
        $data = $this->prepareQuotaDataByPeriod($roomId, $period, type: QuotaTypeEnum::Close);
        \Log::debug('closeRoomQuota', $data);
        EloquentQuota::upsert(
            $data,
            ['room_id', 'date'],
            ['type']
        );
    }

    public function openRoomQuota(int $roomId, CarbonPeriod $period)
    {
        $data = $this->prepareQuotaDataByPeriod($roomId, $period, type: QuotaTypeEnum::Open);
        EloquentQuota::upsert(
            $data,
            ['room_id', 'date'],
            ['type']
        );
    }

    private function prepareQuotaDataByPeriod(
        int $roomId,
        CarbonPeriod $period,
        ?int $quota = null,
        ?int $releaseDays = null,
        ?QuotaTypeEnum $type = null,
    ): array {
        $quotas = [];
        foreach ($period as $date) {
            $quotas[] = [
                'room_id' => $roomId,
                'date' => $date,
                'count_available' => $quota ?? 0,
                'period' => $releaseDays ?? 0,
                'type' => $type ?? ($quota > 0 ? QuotaTypeEnum::Open : QuotaTypeEnum::Close),
            ];
        }

        return $quotas;
    }
}
