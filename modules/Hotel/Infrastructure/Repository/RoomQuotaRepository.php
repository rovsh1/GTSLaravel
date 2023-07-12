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
        EloquentQuota::updateOrInsert(
            ['room_id' => $roomId, 'date' => $period->getStartDate()],
            [
                'room_id' => $roomId,
                'date' => $period->getStartDate(),
                'count_available' => $quota,
                'period' => 0,//@todo в будущем minimumAdvanceBooking
                'type' => $quota > 0 ? QuotaTypeEnum::Open : QuotaTypeEnum::Close,
            ],
        );
    }

    public function closeRoomQuota(int $roomId, CarbonPeriod $period)
    {
        EloquentQuota::whereRoomId($roomId)
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->update(['type' => QuotaTypeEnum::Close]);
    }

    public function openRoomQuota(int $roomId, CarbonPeriod $period)
    {
        EloquentQuota::whereRoomId($roomId)
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->update(['type' => QuotaTypeEnum::Open]);
    }
}
