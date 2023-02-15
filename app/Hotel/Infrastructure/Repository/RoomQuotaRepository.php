<?php

namespace GTS\Hotel\Infrastructure\Repository;

use Carbon\CarbonPeriod;

use GTS\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;
use GTS\Hotel\Infrastructure\Models\Room\Quota as EloquentQuota;
use GTS\Hotel\Infrastructure\Models\Room\QuotaTypeEnum;

class RoomQuotaRepository implements RoomQuotaRepositoryInterface
{
    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota): void
    {
        EloquentQuota::whereRoomId($roomId)
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->update(['count_available' => $quota]);
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
